import { loadSection } from "./../modules/sectionLoader.js";
import { ntfConfirm, ntfProcessError } from "../utils/utils.js";
import { apiRequest } from "../API/api.js";

/**
 * Inicializar todos los eventos/lógica de la sección detailEntity
 */
export async function inicializarDetailEntity() {
  funcionalidadGaleria();
  funcionalidadDocumentosMantenimiento();
  funcionalidadDescripcionMantenimiento();
  funcionalidadBorrar();
}

/**
 * Cargamos todos los detalles relacionado al dato seleccionado
 * @param {HTMLElement} clickedRow - Fila clickeada por el usuario
 * @param {HTMLElement} target - Evento
 */
export function cargarDetallesDeBusqueda(clickedRow, target) {
  // Evita que se disparen otros eventos si hacemos clic en un checkbox o un botón de acción dentro de la fila
  if (
    target.matches('input[type="checkbox"]') ||
    target.matches(".actions span")
  ) {
    return;
  }

  const entityId = clickedRow.dataset.id;
  const tableContainer = clickedRow.closest(".results__content").parentElement;
  let entityType = "";

  // Determinar el tipo de entidad basado en la clase del contenedor padre
  if (tableContainer.classList.contains("vehiculos")) {
    entityType = "vehiculo";
  } else if (tableContainer.classList.contains("personal")) {
    entityType = "personal";
  }

  if (entityId && entityType) {
    // Usamos la función cargarSeccion para mostrar la vista de detalle
    // Pasamos los parámetros como parte del nombre de la sección
    loadSection(
      `detailEntity.php?tipo=${entityType}&id=${entityId}`,
      false,
      document.querySelector("#detailResult-container")
    );
  }
}

/**
 * Inicializar la logica de los documentos de mantenimiento
 */
function funcionalidadDocumentosMantenimiento() {
  const modal = document.getElementById("modal-docs");
  const btnsOpenDocs = document.querySelectorAll(".info-docs");

  inicializadorEventos(modal, btnsOpenDocs);
}

/**
 * Inicializar la logica de la descripcion de mantenimiento
 */
function funcionalidadDescripcionMantenimiento() {
  const modal = document.getElementById("modal-desc");
  const btnsOpenDesc = document.querySelectorAll(".info-desc");

  inicializadorEventos(modal, btnsOpenDesc);
}

/**
 * Inicializar la logica de la galeria
 */
function funcionalidadGaleria() {
  const modal = document.getElementById("modal-galery");
  const modalImg = document.getElementById("modalImg");
  const closeBtn = document.querySelectorAll("#closeBtn");

  if (!modal) {
    return;
  }

  // Detectar clic en cualquier imagen de la galería
  document.querySelectorAll(".galery-container img").forEach((img) => {
    img.addEventListener("click", () => {
      modalImg.dataset.index = img.dataset.index;
      modalImg.dataset.type = img.dataset.type;
      modalImg.dataset.category = img.dataset.category;

      modal.style.display = "flex";
      modalImg.src = img.src;
    });
  });

  // Cerrar modal
  closeBtn.forEach((btn) => {
    btn.addEventListener("click", () => {
      modal.style.display = "none";
      modalImg.dataset.index = "";
      modalImg.dataset.type = "";
      modalImg.dataset.category = "";
    });
  });

  // Cerrar haciendo clic fuera de la imagen
  modal.addEventListener("click", (e) => {
    if (e.target === modal) {
      modal.style.display = "none";
      modalImg.dataset.index = "";
      modalImg.dataset.type = "";
      modalImg.dataset.category = "";
    }
  });
}

/**
 * Inicializar la logica de borrado de archivos
 */
function funcionalidadBorrar() {
  const containerFiles = document.querySelectorAll(".file-list");
  const containerModal = document.querySelector("#modal-galery");
  if (containerFiles) {
    containerFiles.forEach((container) => {
      container.addEventListener("click", async (e) => {
        if (e.target.closest(".btnDeleteDocs")) {
          // MODIFICADO: para detectar clic en el botón o su svg
          const item = e.target.closest("li").querySelector("a");

          if (!item) {
            ntfProcessError(
              "Oops...",
              "Ocurrio un error al tratar de eliminar el archivo!"
            );
            return;
          }

          const indexFile = item.dataset.index;
          const typeFile = item.dataset.type;
          const categoryFile = item.dataset.category;

          const response = await confirmar(e);
          if (response) {
            const status = await almacenarInfoArchivo(
              indexFile,
              typeFile,
              categoryFile
            );

            if (status["success"]) {
              loadSection(
                localStorage.getItem("resultado"),
                false,
                document.querySelector("#detailResult-container")
              );
            } else {
              ntfProcessError("Oops...", status["message"]);
            }
          }
        }
      });
    });
  }

  if (containerModal) {
    containerModal.addEventListener("click", async (e) => {
      if (e.target.matches(".btnDeleteImg")) {
        const img = containerModal.querySelector("img");

        const indexFile = img.dataset.index;
        const typeFile = img.dataset.type;
        const categoryFile = img.dataset.category;

        const response = await confirmar(e);
        if (response) {
          const status = await almacenarInfoArchivo(
            indexFile,
            typeFile,
            categoryFile
          );

          if (status["success"]) {
            loadSection(
              localStorage.getItem("resultado"),
              false,
              document.querySelector("#detailResult-container")
            );
          } else {
            ntfProcessError("Oops...", status["message"]);
          }
        }
      }
    });
  }
}

/**
 * Configurar eventos principales de cada funcionalidad
 */
function inicializadorEventos(modal, btns) {
  const closeBtn = document.querySelectorAll(".closeBtn");

  if (!modal) {
    return;
  }

  btns?.forEach((btn) => {
    btn?.addEventListener("click", async (e) => {
      const row = e.target.closest("tr");
      const id = row?.querySelector("#id-mantenimiento").textContent;

      if (id) {
        modal.setAttribute("data-id", id);
      }

      const formData = new FormData();
      formData.append("section", "mantenimientos");
      formData.append("action", "get");
      formData.append("searchIdInput", id);

      const response = await apiRequest(formData);

      if (response["success"]) {
        // MODIFICADO: Se llama a la función de renderizado correspondiente
        if (modal.id === "modal-docs") {
          mostrarArchivosMantenimiento(response.data);
        } else if (modal.id === "modal-desc") {
          mostrarDescripcionMantenimiento(response.data);
        }
      } else {
        ntfProcessError("Oops...", "No se pudo cargar el contenido!");
      }

      modal.style.display = "flex";
    });
  });

  // Cerrar modal
  closeBtn?.forEach((btn) => {
    btn.addEventListener("click", () => {
      modal.style.display = "none";
    });
  });

  // Cerrar haciendo clic fuera de la imagen
  modal?.addEventListener("click", (e) => {
    if (e.target === modal) {
      modal.style.display = "none";
    }
  });
}

/**
 * Renderiza la lista de archivos de mantenimiento en el modal.
 * @param {object} data - Los datos del mantenimiento recibidos de la API.
 */
function mostrarArchivosMantenimiento(data) {
  const modal = document.getElementById("modal-docs");
  const fileList = modal.querySelector(".file-list");

  // 1. Limpiar la lista anterior
  fileList.innerHTML = "";

  const documentos = data.documentos_existentes;

  // 2. Verificar si hay documentos
  if (documentos && documentos.length > 0) {
    // 3. Crear y añadir cada elemento de la lista
    documentos.forEach((doc, index) => {
      const listItem = document.createElement("li");

      // Se usan plantillas de texto para crear el HTML interno de forma más limpia
      listItem.innerHTML = `
        <a href="${doc.ruta}" download="${doc.nombreOriginal}" data-index="${index}" data-type="documento" data-category="mantenimiento">
            <span>${doc.nombreOriginal}</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down-fill" viewBox="0 0 16 16">
                <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1m-1 4v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 11.293V7.5a.5.5 0 0 1 1 0"/>
            </svg>
        </a>
        <button class="btnDeleteDocs">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
            </svg>
        </button>
      `;
      fileList.appendChild(listItem);
    });
  } else {
    // 4. Si no hay documentos, mostrar un mensaje
    const listItem = document.createElement("li");
    listItem.textContent = "No hay documentos adjuntos.";
    fileList.appendChild(listItem);
  }
}

/**
 * Muestra la descripción del mantenimiento en el modal correspondiente.
 * @param {object} data - Los datos del mantenimiento recibidos de la API.
 */
function mostrarDescripcionMantenimiento(data) {
  const modal = document.getElementById("modal-desc");
  // Asumiendo que tienes un elemento con la clase 'description-content' para mostrar el texto
  const descriptionContent = modal.querySelector(".description-content");

  if (descriptionContent) {
    if (
      data.descripcionMantenimiento &&
      data.descripcionMantenimiento.trim() !== ""
    ) {
      descriptionContent.textContent = data.descripcionMantenimiento;
    } else {
      descriptionContent.textContent =
        "No hay una descripción disponible para este mantenimiento.";
    }
  }
}

/**
 * Aviso de confirmación para borrado de archivos
 */
async function confirmar() {
  const response = await ntfConfirm(
    "¿Seguro que deseas borrar este archivo?",
    "Esta acción es irreversible!"
  );
  return response;
}

/**
 * Inicializar la logica de almacenamiento de informacion del archivo
 * @param indexFile - Indice del archivo
 * @param typeFile - Tipo de archivo a eliminar
 * @param categoryFile - Categoria del archivo
 */
async function almacenarInfoArchivo(indexFile, typeFile, categoryFile) {
  if (indexFile && typeFile && categoryFile) {
    let informacion = {
      indexFile: indexFile,
      typeFile: typeFile,
      categoryFile: categoryFile,
    };

    let id = "";

    switch (categoryFile) {
      case "vehiculo":
        id = document.querySelector("#id-vehiculo span").textContent;

        informacion.ID = id;
        informacion.section = "vehiculos";
        informacion.action = typeFile.includes("documento")
          ? "deleteDoc"
          : "deleteExtra";
        break;
      case "mantenimiento":
        // Aseguramos que el ID se toma del modal correcto
        id = document.querySelector("#modal-docs").dataset.id;

        informacion.ID = id;
        informacion.section = "mantenimientos";
        informacion.action = "deleteDoc";
        break;
      case "personal":
        id = document.querySelector("#id-personal span").textContent;

        informacion.ID = id;
        informacion.section = "personal";
        informacion.action = "deleteDoc";
        break;
    }

    const formData = new FormData();
    Object.entries(informacion).forEach(([key, value]) => {
      formData.append(key, value);
    });
    return await apiRequest(formData);
  }
}
