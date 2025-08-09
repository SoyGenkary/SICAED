const ctx = document.querySelector(".myChart");
const info = {
  elements: ["Vehiculos sin servicio.", "Vehiculos con servicio."],
  values: [237, 253],
};

const myChart = new Chart(ctx, {
  type: "doughnut",
  data: {
    labels: info.elements,
    datasets: [
      {
        data: info.values,
        backgroundColor: ["#f00", "#00f"],
        borderColor: ["#f30", "#03f"],
        borderWidth: 1.5,
        weight: 1,
      },
    ],
  },
});
