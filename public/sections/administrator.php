<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/config/database.php';
?>

<section class="administrator-section">
  <h1 class="principal__title">Administrador de Base de Datos</h1>
  <div class="administrator__container">
    <div class="administrator__header">
      <h2>¿Qué desea administrar?</h2>
    </div>
    <ul class="administrator__list">
      <li class="administrator__item">
        <button class="administrator__button" data-adminsection="vehiculos">
          <div class="administrator__icon">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 512.000000 512.000000"
              preserveAspectRatio="xMidYMid meet"
            >
              <g
                transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
              >
                <path
                  d="M1481 4104 c-131 -35 -153 -56 -496 -459 l-239 -280 -215 -5 c-211 -5 -217 -6 -277 -33 -77 -36 -145 -104 -181 -182 l-28 -60 -3 -278 c-2 -175 1 -298 8 -331 44 -213 225 -356 451 -356 l66 0 -4 88 c-20 362 274 672 637 672 363 0 657 -310 637 -672 l-4 -88 807 0 807 0 -4 88 c-20 362 274 672 637 672 364 0 657 -310 637 -674 l-5 -89 50 6 c118 14 233 100 285 211 l28 61 0 265 0 265 -26 55 c-37 77 -87 131 -156 168 -53 28 -113 41 -556 122 l-497 90 -408 338 c-224 186 -427 350 -452 365 -97 56 -107 57 -805 56 -526 0 -650 -3 -694 -15z m281 -232 l33 -32 0 -205 0 -205 -33 -32 -32 -33 -300 -3 c-290 -3 -300 -2 -320 18 -11 11 -20 29 -20 40 0 22 345 433 393 468 30 22 40 23 139 20 106 -3 108 -3 140 -36z m1089 8 c24 -16 157 -119 294 -228 220 -174 250 -202 253 -229 7 -67 37 -64 -639 -61 l-609 3 -32 33 -33 32 0 204 c0 204 0 204 25 231 14 15 34 31 45 35 11 5 162 9 336 9 l316 1 44 -30z m-369 -758 c28 -28 33 -39 33 -82 0 -43 -5 -54 -33 -82 l-32 -33 -130 0 -130 0 -32 33 c-28 28 -33 39 -33 81 0 39 5 55 25 76 38 41 55 45 182 42 l118 -2 32 -33z"
                />
                <path
                  d="M1076 2744 c-182 -44 -337 -201 -381 -385 -88 -373 251 -712 624 -624 187 44 342 199 386 386 41 176 -10 355 -140 484 -130 130 -312 182 -489 139z m198 -276 c55 -16 138 -99 154 -154 28 -94 8 -169 -63 -239 -101 -102 -229 -102 -330 0 -102 101 -102 229 0 330 70 71 145 90 239 63z"
                />
                <path
                  d="M3956 2744 c-182 -44 -337 -201 -381 -385 -88 -373 251 -712 624 -624 276 66 451 348 386 624 -66 277 -351 452 -629 385z m198 -276 c86 -26 166 -136 166 -228 0 -124 -116 -240 -240 -240 -124 0 -240 116 -240 240 0 63 23 114 75 165 70 71 145 90 239 63z"
                />
              </g>
            </svg>
          </div>
          <span>Vehículos</span>
        </button>
      </li>
      <li class="administrator__item">
        <button class="administrator__button" data-adminsection="conductores">
          <div class="administrator__icon">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 512.000000 512.000000"
              preserveAspectRatio="xMidYMid meet"
            >
              <g
                transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
              >
                <path
                  d="M2396 5105 c-351 -73 -615 -319 -707 -660 -32 -115 -32 -335 0 -450 85 -314 337 -564 651 -647 109 -28 317 -31 423 -5 393 97 671 430 694 832 24 420 -262 809 -672 916 -104 27 -293 34 -389 14z"
                />
                <path
                  d="M1425 3152 c-107 -37 -190 -105 -235 -192 -18 -37 -419 -1026 -445 -1100 -19 -55 -19 -164 0 -221 9 -24 30 -63 48 -86 18 -24 166 -155 329 -293 277 -232 302 -251 350 -260 28 -6 53 -8 56 -5 6 5 172 512 172 523 0 5 -37 19 -82 31 -63 18 -102 22 -168 19 l-85 -3 -6 145 c-3 80 -4 147 -2 149 1 2 33 6 70 9 67 5 68 5 108 54 52 65 184 181 271 240 341 228 773 290 1164 166 229 -73 467 -229 604 -396 l49 -60 69 -4 c38 -3 70 -7 71 -9 2 -2 1 -69 -2 -149 l-6 -145 -85 3 c-66 3 -105 -1 -168 -19 -45 -12 -82 -26 -82 -31 0 -4 38 -124 85 -265 l84 -258 42 3 c23 2 50 7 59 10 32 13 615 513 643 552 15 21 34 58 42 81 19 53 19 165 0 218 -18 53 -400 999 -431 1070 -48 108 -130 182 -242 220 -61 21 -76 21 -1144 21 -1001 -1 -1087 -2 -1133 -18z"
                />
                <path
                  d="M2350 2071 c-154 -34 -266 -83 -396 -172 -87 -60 -123 -96 -103 -103 8 -3 60 -18 117 -35 57 -16 106 -32 110 -36 3 -3 -14 -65 -38 -138 -24 -73 -62 -192 -86 -264 l-42 -133 86 1 87 0 34 72 c89 187 306 301 512 270 160 -25 302 -129 370 -270 l34 -72 87 0 86 -1 -42 133 c-24 72 -62 191 -86 264 -24 73 -42 135 -38 138 3 3 56 20 117 38 62 18 113 37 114 42 1 14 -107 100 -183 143 -168 97 -300 133 -505 138 -120 3 -167 0 -235 -15z"
                />
                <path
                  d="M2485 1221 c-68 -30 -125 -113 -125 -181 0 -79 66 -162 146 -186 49 -14 59 -14 108 0 80 24 146 107 146 186 0 37 -24 94 -53 127 -60 69 -145 89 -222 54z"
                />
                <path
                  d="M1775 770 c-35 -107 -41 -119 -59 -115 -74 17 -136 21 -136 10 0 -27 85 -180 141 -254 143 -190 350 -329 578 -387 51 -13 97 -24 102 -24 5 0 9 127 9 283 l0 282 -69 32 c-92 43 -179 130 -223 223 l-33 69 -135 0 -136 1 -39 -120z"
                />
                <path
                  d="M3003 821 c-44 -92 -132 -180 -224 -224 l-69 -32 0 -282 c0 -156 4 -283 9 -283 5 0 51 11 104 24 288 73 542 279 677 548 44 89 49 112 23 104 -10 -2 -38 -7 -63 -11 -25 -4 -52 -8 -60 -11 -12 -3 -25 23 -55 116 l-39 120 -136 0 -135 0 -32 -69z"
                />
              </g>
            </svg>
          </div>
          <span>Conductores</span>
        </button>
      </li>
      <li class="administrator__item">
        <button class="administrator__button" data-adminsection="personal">
          <div class="administrator__icon">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 512.000000 512.000000"
              preserveAspectRatio="xMidYMid meet"
            >
              <g
                transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
              >
                <path
                  d="M2475 4867 c-22 -8 -53 -21 -70 -29 -32 -17 -115 -96 -115 -110 0 -4 122 -8 270 -8 149 0 270 4 270 8 0 20 -89 97 -138 120 -65 29 -160 38 -217 19z"
                />
                <path
                  d="M2245 4513 c8 -53 41 -125 79 -170 14 -16 53 -45 88 -64 58 -31 70 -34 148 -34 78 0 90 3 148 34 35 19 74 48 88 64 38 45 71 117 79 170 l7 47 -322 0 -322 0 7 -47z"
                />
                <path
                  d="M4245 4548 c-16 -6 -43 -20 -58 -30 l-28 -20 38 -27 c99 -72 172 -188 194 -306 6 -33 12 -61 14 -63 8 -9 67 32 97 68 84 97 77 222 -17 315 -69 70 -157 92 -240 63z"
                />
                <path
                  d="M1115 4387 c-22 -8 -53 -21 -70 -29 -32 -17 -115 -96 -115 -110 0 -4 122 -8 270 -8 149 0 270 4 270 8 0 20 -89 97 -138 120 -65 29 -160 38 -217 19z"
                />
                <path
                  d="M3835 4387 c-22 -8 -53 -21 -70 -29 -32 -17 -115 -96 -115 -110 0 -4 122 -8 270 -8 149 0 270 4 270 8 0 20 -89 97 -138 120 -65 29 -160 38 -217 19z"
                />
                <path
                  d="M885 4033 c8 -53 41 -125 79 -170 14 -16 53 -45 88 -64 58 -31 70 -34 148 -34 78 0 90 3 148 34 35 19 74 48 88 64 38 45 71 117 79 170 l7 47 -322 0 -322 0 7 -47z"
                />
                <path
                  d="M2404 4048 c7 -39 31 -74 70 -102 39 -29 133 -29 172 0 39 28 63 63 70 102 l7 32 -163 0 -162 0 6 -32z"
                />
                <path
                  d="M3605 4033 c8 -53 41 -125 79 -170 14 -16 53 -45 88 -64 58 -31 70 -34 148 -34 78 0 90 3 148 34 35 19 74 48 88 64 38 45 71 117 79 170 l7 47 -322 0 -322 0 7 -47z"
                />
                <path
                  d="M2178 3983 c-48 -49 -87 -121 -88 -160 0 -13 2 -23 4 -23 2 0 37 16 79 35 42 19 82 35 89 35 36 1 39 16 13 71 -13 30 -25 62 -25 72 0 29 -24 19 -72 -30z"
                />
                <path
                  d="M2870 4013 c0 -10 -11 -42 -25 -71 -29 -63 -29 -62 -12 -62 6 0 48 -16 92 -36 44 -19 86 -38 93 -41 19 -8 14 35 -9 87 -21 48 -105 140 -126 140 -7 0 -13 -8 -13 -17z"
                />
                <path
                  d="M2410 3746 c-333 -71 -572 -313 -636 -644 -11 -58 -14 -188 -14 -602 l0 -527 58 19 c31 10 112 28 180 39 68 11 133 24 146 28 29 12 86 77 85 99 0 9 -27 37 -61 61 -74 54 -138 133 -184 228 -54 111 -64 176 -64 433 l0 225 111 23 c62 13 137 32 168 42 73 26 179 99 236 162 l45 50 0 109 0 109 80 0 80 0 0 -109 0 -109 46 -51 c95 -105 226 -170 422 -207 l92 -18 0 -230 c0 -165 -5 -251 -15 -301 -31 -144 -124 -288 -238 -370 -31 -22 -56 -45 -57 -52 0 -18 60 -84 86 -94 13 -4 80 -17 149 -29 69 -11 151 -28 181 -39 l55 -18 -3 561 c-3 484 -6 569 -20 616 -32 107 -60 170 -107 242 -114 174 -272 288 -481 345 -78 21 -260 26 -340 9z"
                />
                <path
                  d="M1044 3568 c7 -39 31 -74 70 -102 39 -29 133 -29 172 0 39 28 63 63 70 102 l7 32 -163 0 -162 0 6 -32z"
                />
                <path
                  d="M3764 3568 c7 -39 31 -74 70 -102 39 -29 133 -29 172 0 39 28 63 63 70 102 l7 32 -163 0 -162 0 6 -32z"
                />
                <path
                  d="M816 3504 c-25 -25 -46 -50 -46 -55 0 -5 34 -9 76 -9 57 0 74 3 70 13 -3 6 -11 31 -18 55 -7 23 -18 42 -24 42 -7 0 -33 -21 -58 -46z"
                />
                <path
                  d="M1510 3533 c0 -10 -11 -41 -24 -70 l-25 -52 52 -24 c29 -13 73 -40 99 -60 25 -19 49 -33 52 -30 28 28 -22 151 -84 212 -45 44 -70 52 -70 24z"
                />
                <path
                  d="M3540 3509 c-43 -42 -90 -128 -90 -166 0 -12 4 -33 10 -48 l10 -26 52 39 c29 22 73 50 98 62 25 12 46 23 47 24 1 1 -11 28 -27 60 -17 32 -30 67 -30 77 0 30 -24 22 -70 -22z"
                />
                <path
                  d="M4230 3533 c0 -10 -9 -37 -19 -60 l-19 -41 36 -7 c21 -4 63 -17 95 -30 31 -12 57 -19 57 -13 0 22 -41 86 -81 126 -46 45 -69 53 -69 25z"
                />
                <path
                  d="M584 3255 l-26 -26 4 -262 c4 -246 5 -266 27 -322 13 -33 30 -69 37 -80 13 -18 14 -17 14 16 0 82 58 177 132 215 15 8 87 24 160 35 164 25 204 45 242 121 14 28 26 60 26 70 0 16 10 18 81 18 l82 0 -6 -43 c-3 -24 -9 -49 -12 -57 -3 -10 12 -24 50 -44 90 -47 170 -151 194 -253 6 -27 9 25 10 168 1 113 4 227 7 252 6 44 5 47 -45 97 -30 30 -79 64 -119 83 l-67 32 -382 3 -383 3 -26 -26z"
                />
                <path
                  d="M3797 3269 c-78 -18 -152 -62 -221 -131 -46 -46 -66 -73 -62 -84 3 -9 6 -113 7 -232 1 -150 4 -205 10 -179 24 102 104 206 194 253 38 20 53 34 50 44 -3 8 -9 33 -12 57 l-6 43 80 0 79 0 13 -48 c13 -53 51 -101 98 -126 17 -8 90 -24 162 -35 72 -12 144 -27 159 -35 73 -38 132 -134 132 -214 0 -34 1 -35 14 -17 28 41 57 141 62 214 11 147 -37 271 -144 373 -109 104 -189 128 -422 127 -85 -1 -172 -5 -193 -10z"
                />
                <path
                  d="M2487 3160 c-103 -88 -248 -158 -362 -175 l-45 -7 0 -157 c0 -171 11 -234 56 -323 34 -67 135 -168 202 -202 239 -121 526 -36 645 191 47 92 57 147 57 331 l0 157 -80 18 c-106 24 -238 91 -327 166 -39 34 -73 61 -75 60 -2 0 -34 -27 -71 -59z m-72 -523 c24 -39 96 -77 145 -77 64 1 145 52 163 103 3 8 27 1 75 -22 40 -19 72 -38 72 -41 0 -14 -67 -99 -97 -124 -61 -51 -120 -71 -213 -71 -71 0 -94 4 -140 26 -30 15 -69 40 -86 56 -33 31 -90 111 -83 117 10 9 125 65 134 65 6 0 19 -14 30 -32z"
                />
                <path
                  d="M1215 2755 c-45 -38 -119 -61 -245 -80 -124 -18 -156 -31 -166 -70 -3 -14 -4 -85 -2 -158 4 -127 5 -134 38 -195 37 -70 68 -100 144 -140 43 -22 64 -26 136 -26 72 0 93 4 136 26 76 40 107 70 144 140 33 60 34 68 38 191 5 153 -5 200 -59 261 -37 42 -92 76 -121 76 -7 -1 -26 -12 -43 -25z"
                />
                <path
                  d="M3812 2763 c-48 -23 -107 -96 -122 -148 -8 -29 -11 -88 -8 -172 4 -123 5 -131 38 -191 37 -70 68 -100 144 -140 43 -22 64 -26 136 -26 72 0 93 4 136 26 76 40 107 70 144 140 33 61 34 68 38 195 2 73 1 144 -2 158 -10 39 -42 52 -166 70 -127 19 -194 40 -245 79 -39 30 -49 31 -93 9z"
                />
                <path
                  d="M2360 2056 c-28 -58 -99 -121 -162 -144 -26 -9 -47 -20 -46 -24 2 -3 37 -70 78 -147 l75 -141 255 0 255 0 75 141 c90 168 86 159 63 159 -53 0 -166 94 -200 166 -16 32 -18 33 -57 26 -55 -9 -199 -9 -262 1 l-51 9 -23 -46z"
                />
                <path
                  d="M986 1931 c-2 -3 -6 -17 -9 -31 -5 -22 2 -30 68 -74 l75 -48 75 48 c66 44 73 52 68 74 -9 38 -10 39 -50 29 -21 -5 -63 -9 -93 -9 -30 0 -72 4 -93 9 -20 5 -39 6 -41 2z"
                />
                <path
                  d="M3866 1931 c-2 -3 -6 -17 -9 -31 -5 -22 2 -30 68 -74 l75 -48 75 48 c66 44 73 52 68 74 -9 38 -10 39 -50 29 -21 -5 -63 -9 -93 -9 -30 0 -72 4 -93 9 -20 5 -39 6 -41 2z"
                />
                <path
                  d="M1901 1849 c-205 -52 -370 -186 -464 -374 -60 -119 -69 -168 -74 -392 l-5 -203 161 0 161 0 0 84 c0 46 7 122 15 169 16 90 62 247 72 247 4 0 38 -10 76 -22 78 -26 75 -12 32 -153 -24 -78 -28 -107 -29 -225 -1 -159 7 -194 94 -415 57 -144 60 -155 60 -238 l0 -87 240 0 240 0 0 350 0 350 -246 457 c-136 252 -249 461 -253 465 -3 3 -39 -2 -80 -13z"
                />
                <path
                  d="M3128 1847 c-6 -12 -118 -221 -250 -465 l-238 -442 0 -350 0 -350 240 0 240 0 0 87 c0 83 3 94 60 238 86 219 95 256 94 415 -1 118 -4 146 -29 225 -43 141 -46 127 32 153 38 12 72 22 76 22 10 0 56 -157 72 -247 8 -47 15 -123 15 -169 l0 -84 161 0 161 0 -5 203 c-5 218 -15 274 -66 377 -106 212 -268 342 -493 395 -56 13 -59 13 -70 -8z"
                />
                <path
                  d="M768 1738 c-27 -6 -48 -14 -48 -19 0 -5 14 -34 31 -64 l30 -54 85 42 c46 23 84 44 84 48 0 3 -18 18 -40 33 -42 27 -65 30 -142 14z"
                />
                <path
                  d="M1328 1723 c-21 -14 -38 -29 -38 -33 0 -4 14 -13 32 -21 30 -12 32 -12 64 27 19 21 34 42 34 47 0 16 -57 4 -92 -20z"
                />
                <path
                  d="M3700 1743 c0 -5 15 -26 34 -47 32 -39 34 -39 64 -27 18 8 32 17 32 21 0 14 -80 60 -105 60 -14 0 -25 -3 -25 -7z"
                />
                <path
                  d="M4208 1723 c-21 -14 -38 -29 -38 -32 0 -4 38 -25 84 -48 l85 -42 30 54 c17 30 31 59 31 63 0 10 -99 32 -134 31 -12 0 -38 -12 -58 -26z"
                />
                <path
                  d="M475 1651 c-104 -60 -199 -191 -224 -309 -7 -35 -11 -227 -11 -577 l0 -525 80 0 80 0 0 440 0 440 80 0 80 0 0 -440 0 -440 240 0 240 0 0 270 0 270 -242 450 c-155 286 -248 450 -258 450 -8 0 -37 -13 -65 -29z"
                />
                <path
                  d="M4323 1230 l-243 -450 0 -270 0 -270 240 0 240 0 0 440 0 440 80 0 80 0 0 -440 0 -440 80 0 80 0 0 528 c0 572 -1 586 -56 694 -46 91 -188 218 -244 218 -10 0 -103 -164 -257 -450z"
                />
                <path
                  d="M1087 1574 c-30 -16 -30 -14 -2 -94 l12 -35 76 -3 75 -3 12 36 c7 20 10 38 8 39 -5 5 -147 76 -150 76 -2 -1 -16 -8 -31 -16z"
                />
                <path
                  d="M3925 1553 c-38 -19 -72 -37 -73 -39 -2 -1 1 -19 8 -39 l12 -36 75 3 76 3 12 35 c28 81 28 78 -4 94 -17 9 -31 16 -33 16 -2 -1 -34 -17 -73 -37z"
                />
                <path
                  d="M876 1467 c-17 -12 -16 -17 16 -75 18 -34 36 -62 40 -62 17 0 18 44 2 95 -18 57 -28 64 -58 42z"
                />
                <path
                  d="M4186 1425 c-16 -51 -15 -95 2 -95 4 0 22 28 40 62 32 58 33 63 16 75 -30 22 -40 15 -58 -42z"
                />
                <path
                  d="M2400 1433 c0 -11 154 -293 160 -293 6 0 160 282 160 293 0 4 -72 7 -160 7 -88 0 -160 -3 -160 -7z"
                />
                <path
                  d="M1057 1214 l-32 -65 44 -85 c24 -46 47 -84 51 -84 3 0 22 30 42 68 30 57 36 80 41 150 l5 82 -59 0 -59 0 -33 -66z"
                />
                <path
                  d="M3917 1198 c5 -70 11 -93 41 -150 20 -38 39 -68 42 -68 4 0 27 38 51 84 l44 85 -32 65 -33 66 -59 0 -59 0 5 -82z"
                />
                <path
                  d="M1206 793 c-3 -8 -6 -136 -6 -284 l0 -269 80 0 80 0 0 239 0 239 -36 6 c-22 5 -50 22 -74 46 -30 30 -40 35 -44 23z"
                />
                <path
                  d="M3868 769 c-23 -24 -50 -40 -72 -45 l-36 -6 0 -239 0 -239 80 0 80 0 0 273 c0 149 -4 277 -8 283 -4 6 -21 -4 -44 -27z"
                />
                <path
                  d="M1520 480 l0 -240 160 0 160 0 0 72 c0 65 -6 88 -60 224 -33 83 -60 159 -60 168 0 14 -14 16 -100 16 l-100 0 0 -240z"
                />
                <path
                  d="M3400 704 c0 -9 -27 -85 -60 -168 -54 -136 -60 -159 -60 -224 l0 -72 160 0 160 0 0 240 0 240 -100 0 c-86 0 -100 -2 -100 -16z"
                />
              </g>
            </svg>
          </div>
          <span>Personal</span>
        </button>
      </li>
      <li class="administrator__item">
        <button
          class="administrator__button"
          data-adminsection="mantenimientos"
        >
          <div class="administrator__icon">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 512.000000 512.000000"
              preserveAspectRatio="xMidYMid meet"
            >
              <g
                transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
              >
                <path
                  d="M2237 5088 c-4 -18 -14 -97 -23 -175 l-17 -142 -31 -6 c-89 -17 -183 -40 -284 -72 -100 -30 -115 -32 -125 -19 -7 8 -52 69 -102 135 -63 84 -95 120 -107 117 -14 -3 -483 -269 -529 -300 -14 -10 -8 -30 52 -171 l68 -160 -89 -83 c-49 -46 -120 -116 -157 -157 l-68 -74 -160 68 c-141 60 -161 66 -171 52 -31 -46 -297 -515 -300 -529 -3 -12 33 -44 117 -107 66 -49 127 -95 135 -102 13 -10 11 -25 -19 -125 -32 -101 -55 -195 -72 -284 l-6 -31 -142 -17 c-78 -9 -157 -19 -175 -23 l-32 -5 0 -318 0 -318 33 -5 c17 -4 96 -14 174 -23 l142 -17 6 -31 c17 -89 40 -183 72 -284 30 -100 32 -115 19 -125 -8 -7 -69 -52 -135 -102 -66 -49 -120 -94 -121 -99 0 -12 143 -265 150 -266 3 0 156 151 339 334 l335 335 -33 103 c-102 324 -99 697 8 1018 347 1038 1584 1462 2491 854 375 -251 633 -647 711 -1093 29 -161 29 -401 0 -562 -106 -603 -535 -1100 -1115 -1291 -325 -107 -690 -109 -1014 -7 l-103 33 -335 -335 c-183 -183 -334 -336 -334 -339 1 -7 254 -150 266 -150 5 1 50 55 99 121 50 66 95 127 102 135 10 13 25 11 125 -19 101 -32 195 -55 284 -72 l31 -6 17 -142 c9 -78 19 -157 23 -175 l5 -32 318 0 318 0 5 33 c4 17 14 96 23 173 l17 141 106 23 c58 12 154 38 214 57 96 30 110 32 120 19 7 -8 53 -69 102 -135 50 -66 95 -120 100 -121 9 0 545 305 551 313 1 1 -29 74 -67 162 l-68 160 74 68 c41 37 111 108 157 157 l83 89 161 -68 161 -68 153 265 c84 145 154 271 156 281 3 11 -35 45 -122 111 -70 52 -131 98 -136 101 -6 4 5 53 25 120 31 102 55 195 72 285 l6 31 142 17 c78 9 157 19 175 23 l32 5 0 318 0 318 -32 5 c-18 4 -97 14 -175 23 l-142 17 -6 31 c-17 90 -41 183 -72 285 -20 67 -31 116 -25 120 5 3 66 49 136 101 87 66 125 100 122 111 -2 10 -72 136 -156 281 l-153 265 -161 -68 -161 -68 -83 89 c-46 49 -116 120 -157 157 l-74 68 68 160 c38 88 68 161 67 162 -6 8 -542 313 -551 313 -5 -1 -50 -55 -100 -121 -49 -66 -95 -127 -102 -135 -10 -13 -24 -11 -120 19 -60 19 -156 45 -214 57 l-106 23 -17 141 c-9 77 -19 156 -23 174 l-5 32 -318 0 -318 0 -5 -32z"
                />
                <path
                  d="M2380 3446 c-185 -43 -314 -111 -444 -235 -185 -177 -276 -394 -276 -654 0 -114 6 -156 37 -260 l17 -58 -746 -747 c-410 -411 -754 -763 -766 -782 -53 -91 -65 -203 -31 -303 72 -215 316 -313 523 -210 40 19 215 188 798 771 l747 746 58 -17 c104 -30 146 -37 255 -37 261 0 460 81 643 265 194 195 275 406 262 683 -5 118 -18 180 -57 286 l-22 59 -347 -347 -346 -346 -125 0 -125 0 -87 87 -88 88 0 125 0 125 346 346 347 347 -59 22 c-32 12 -84 28 -114 37 -74 21 -326 27 -400 9z"
                />
              </g>
            </svg>
          </div>
          <span>Servicios (Mantenimiento)</span>
        </button>
      </li>
    </ul>
  </div>
  <div class="administrator__modal close">
    <div class="administrator__modal-content">
      <div class="header-group">
        <div class="administrator__modal-header">
          <h2 class="administrator__modal-title"></h2>
          <button class="administrator__modal-close" aria-label="Cerrar modal">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              height="24px"
              viewBox="0 -960 960 960"
              width="24px"
              fill="#000"
            >
              <path
                d="M480-385 302-207q-20 20-48 20t-47-20q-20-20-20-47.5t20-47.5l178-178-178-179q-20-20-20-47.5t20-47.5q19-20 47-20t48 20l178 178 178-178q20-20 48-20t47 20q20 20 20 47.5T753-659L575-480l178 178q20 20 20 47.5T753-207q-19 20-47 20t-48-20L480-385Z"
              />
            </svg>
          </button>
        </div>
        <div class="administrator__actions">
          <ul class="administrator__actions-list">
            <li class="administrator__action-item">
              <button
                class="administrator__action-button active"
                data-action="add"
              >
                Agregar
              </button>
            </li>
            <li class="administrator__action-item">
              <button class="administrator__action-button" data-action="delete">
                Borrar
              </button>
            </li>
            <li class="administrator__action-item">
              <button class="administrator__action-button" data-action="modify">
                Modificar
              </button>
            </li>
          </ul>
        </div>
      </div>
      <div class="administrator__modal-body">
        <div class="administrator__form-container">
          <form
            class="administrator__modal-form"
            id="formVehiculos"
            data-sectionmodal="vehiculos"
            style="display: none"
            enctype="multipart/form-data"
          >
            <div class="preview-matricula">
              <img
                src="./assets/img/matricula/matricula_plantilla.png"
                alt="Plantilla de una Matricula para la Preview"
              />
              <span id="previewMatriculaVehiculo">MATRICULA</span>
              <span id="previewEstadoVehiculo">ESTADO</span>
            </div>
            <div class="administrator__basic-info">
              <div class="administrator__form-group">
                <label for="matriculaVehiculo"
                  ><span class="administrator__form-label"
                    >Matrícula:</span
                  ></label
                >
                <input type="hidden" name="id_vehiculo" id="id_vehiculo" />
                <input
                  type="text"
                  class="administrator__form-input inputStyle"
                  name="matriculaVehiculo"
                  id="matriculaVehiculo"
                  placeholder="Ingresa la matricula del vehiculo"
                />
              </div>
              <div class="administrator__form-group">
                <label for="estadoSedeVehiculo"
                  ><span class="administrator__form-label">Estado:</span></label
                >
                <select
                  name="estadoSedeVehiculo"
                  id="estadoSedeVehiculo"
                  class="inputStyle"
                  required
                >
                  <option value="" disabled selected>
                    --Elige el Estado--
                  </option>
                </select>
              </div>
            </div>
            <div class="administrator__basic-info">
              <div class="administrator__form-group">
                <label for="vinVehiculo"
                  ><span class="administrator__form-label">Vin:</span></label
                >
                <input
                  type="text"
                  class="administrator__form-input inputStyle"
                  name="vinVehiculo"
                  id="vinVehiculo"
                  placeholder="Ingresa el Vin del vehiculo"
                />
              </div>
              <div class="administrator__form-group">
                <label for="municipioVehiculo"
                  ><span class="administrator__form-label"
                    >Municipio:</span
                  ></label
                >
                <select
                  name="municipioVehiculo"
                  id="municipioVehiculo"
                  class="inputStyle"
                >
                  <option value="" selected disabled>
                    --Elige el Municipio--
                  </option>
                </select>
              </div>
            </div>
            <div class="administrator__basic-info">
              <div class="administrator__form-group">
                <label for="marcaVehiculo"
                  ><span class="administrator__form-label"
                    >Marca del Vehículo:</span
                  ></label
                >
                <input
                  type="text"
                  class="administrator__form-input inputStyle"
                  name="marcaVehiculo"
                  id="marcaVehiculo"
                  placeholder="Ingresa la marca del vehiculo"
                  required
                />
              </div>
              <div class="administrator__form-group">
                <label for="modeloVehiculo"
                  ><span class="administrator__form-label"
                    >Modelo del Vehículo:</span
                  ></label
                >
                <input
                  type="text"
                  class="administrator__form-input inputStyle"
                  name="modeloVehiculo"
                  id="modeloVehiculo"
                  placeholder="Ingresa el modelo del vehiculo"
                  required
                />
              </div>
              <div class="administrator__form-group">
                <label for="sedeVehiculo"
                  ><span class="administrator__form-label">Sede:</span></label
                >
                <input
                  type="text"
                  class="administrator__form-input inputStyle"
                  name="sedeVehiculo"
                  id="sedeVehiculo"
                  placeholder="Ingresa la sede del estado"
                  required
                />
              </div>
            </div>
            <div class='administrator__form-group'>
                <label for="descripcionUbicacion">
                  <span class='administrator__form-label'>Ubicacion:</span>
                  <textarea
                    class="administrator__form-input inputStyle descripcionUbicacion"
                    name="descripcionUbicacion"
                    id='descripcionUbicacion'
                    rows="3"
                    placeholder="Ej. Zona Educativa, ..."
                  ></textarea>
                </label>
                <small style="font-size: .83rem; color: blue">*Atajos comunes:</small>
                <div class="acortadores">
                  <span id="transporte" class='atajo'>División de Transporte.</span>
                  <span id="estado" class='atajo'>Estado.</span>
                </div>
            </div>
            <div class="administrator__form-group">
                <label for="kilometrajeVehiculo"
                  ><span class="administrator__form-label">Kilometraje (KM):</span></label
                >
                <input
                  type="text"
                  class="administrator__form-input inputStyle"
                  name="kilometrajeVehiculo"
                  id="kilometrajeVehiculo"
                  placeholder="Ingresa el kilometraje"
                  required
                />
              </div>
            <div class="administrator__basic-info">
              <div class="administrator__form-group">
                <label for="fotoVehiculo"
                  ><span class="administrator__form-label"
                    >Adjuntar Foto del Vehículo<br><small>(Foto que tendra de perfil)</small></span
                  ></label
                >
                <div class="administrator__form-file">
                  <input
                    type="file"
                    class="administrator__form-input inputStyle"
                    name="fotoVehiculo"
                    id="fotoVehiculo"
                    accept="image/*"
                  />
                </div>
              </div>
              <div class="administrator__form-group">
                <label for="archivosVehiculo"
                  ><span class="administrator__form-label"
                    >Adjuntar Documentos<br><small>(Documentos relacionados al vehiculo)</small></span
                  ></label
                >
                <div class="administrator__form-file">
                  <input
                    type="file"
                    class="administrator__form-input inputStyle"
                    name="archivosVehiculo[]"
                    id="archivosVehiculo"
                    multiple
                  />
                </div>
              </div>
            </div>
            <div class="administrator__form-group">
                <label for="fotosVehiculo"
                  ><span class="administrator__form-label"
                    >Adjuntar Fotos<br><small>(Fotos adicionales del vehiculo)</small></span
                  ></label
                >
                <div class="administrator__form-file">
                  <input
                    type="file"
                    class="administrator__form-input inputStyle"
                    name="fotosVehiculo[]"
                    id="fotosVehiculo"
                    accept="image/*"
                    multiple
                  />
                </div>
              </div>
            <div class="administrator__form-group">
              <label for="asignarPersonalVehiculo"
                ><span class="administrator__form-label"
                  >¿Asignar Personal?</span
                ></label
              >
              <input
                type="checkbox"
                class="administrator__form-input"
                name="asignarPersonalVehiculo"
                id="asignarPersonalVehiculo"
              />
            </div>
            <div
              id="camposAsignarPersonalVehiculo"
              class="administrator__conditional-fields"
              style="display: none"
            >
              <div class="administrator__form-group">
                <label for="cedulaPersonalAsignado"
                  ><span class="administrator__form-label"
                    >Cédula del Personal:</span
                  ></label
                >
                <div class="input-group">
                  <select
                    name="prefijoCedulaPersonalAsignado"
                    id="prefijoCedulaPersonalAsignado"
                    class="inputStyle"
                    style="width: auto"
                  >
                    <option value="V-">V-</option>
                    <option value="E-">E-</option>
                  </select>
                  <input
                    type="text"
                    class="administrator__form-input inputStyle"
                    name="cedulaPersonalAsignado"
                    id="cedulaPersonalAsignado"
                    placeholder="Ej: 12345678"
                  />
                </div>
              </div>
            </div>
            <input
              type="submit"
              name="registrarVehiculo"
              id="registrarVehiculo"
              class="administrator__form-submit"
              value="Registrar Vehículo"
            />
          </form>

          <form
            class="administrator__modal-form"
            id="formConductores"
            data-sectionmodal="conductores"
            style="display: none"
            method="post"
            enctype="multipart/form-data"
          >
            <div class="administrator__form-group">
              <label for="usarPersonalExistenteConductor"
                ><span class="administrator__form-label"
                  >¿Usar Personal Existente?</span
                ></label
              >
              <input
                type="checkbox"
                class="administrator__form-input"
                name="usarPersonalExistenteConductor"
                id="usarPersonalExistenteConductor"
              />
            </div>
            <div
              id="camposPersonalExistenteConductor"
              class="administrator__conditional-fields"
              style="display: none"
            >
              <div class="administrator__form-group">
                <label for="cedulaPersonalExistenteConductor"
                  ><span class="administrator__form-label"
                    >Cédula del Personal Existente:</span
                  ></label
                >
                <div class="input-group">
                  <select
                    name="prefijoCedulaPersonalExistenteConductor"
                    id="prefijoCedulaPersonalExistenteConductor"
                    class="inputStyle"
                    style="width: auto"
                  >
                    <option value="V-">V-</option>
                    <option value="E-">E-</option>
                  </select>
                  <input
                    type="text"
                    class="administrator__form-input inputStyle"
                    name="cedulaPersonalExistenteConductor"
                    id="cedulaPersonalExistenteConductor"
                    placeholder="Ej: 12345678"
                    title="Ingrese de 7 a 8 dígitos"
                  />
                </div>
              </div>
            </div>
            <div id="camposNuevosDatosConductor">
              <div class="administrator__basic-info">
                <div class="administrator__form-group">
                  <label for="nombreConductor"
                    ><span class="administrator__form-label"
                      >Nombres:</span
                    ></label
                  >
                  <input type="hidden" name="id_personal" id="id_personal" />
                  <input
                    type="text"
                    class="administrator__form-input inputStyle"
                    name="nombreConductor"
                    id="nombreConductor"
                    placeholder="Ej: Juan Carlos"
                    required
                  />
                </div>
                <div class="administrator__form-group">
                  <label for="apellidoConductor"
                    ><span class="administrator__form-label"
                      >Apellidos:</span
                    ></label
                  >
                  <input
                    type="text"
                    class="administrator__form-input inputStyle"
                    name="apellidoConductor"
                    id="apellidoConductor"
                    placeholder="Ej: Pérez Rodríguez"
                    required
                  />
                </div>
              </div>
              <div class="administrator__basic-info">
                <div class="administrator__form-group">
                  <label for="cedulaConductor"
                    ><span class="administrator__form-label"
                      >Cédula:</span
                    ></label
                  >
                  <div class="input-group">
                    <select
                      name="prefijoCedulaConductor"
                      id="prefijoCedulaConductor"
                      class="inputStyle"
                      style="width: auto"
                    >
                      <option value="V-">V-</option>
                      <option value="E-">E-</option>
                    </select>
                    <input
                      type="text"
                      class="administrator__form-input inputStyle"
                      name="cedulaConductor"
                      id="cedulaConductor"
                      placeholder="Ej: 12345678"
                      title="Ingrese de 7 a 8 dígitos"
                      required
                    />
                  </div>
                </div>
                <div class="administrator__form-group">
                  <label for="telefonoConductor"
                    ><span class="administrator__form-label"
                      >Teléfono:</span
                    ></label
                  >
                  <input
                    type="tel"
                    class="administrator__form-input inputStyle"
                    name="telefonoConductor"
                    id="telefonoConductor"
                    placeholder="Ej: 0412-1234567"
                    title="Formato: 0XXX-XXXXXXX"
                    required
                  />
                </div>
              </div>
              <!--GRUPO DE SELECTORES UBICACION CONDUCTOR-->
              <div class="administrator__basic-info">
                <!--SELECTOR ESTADO CONDUCTOR-->
                <div class="administrator__form-group">
                  <label for="estadoSedeConductor"
                    ><span class="administrator__form-label"
                      >Estado:</span
                    ></label
                  >
                  <select
                    name="estadoSedeConductor"
                    id="estadoSedeConductor"
                    class="inputStyle"
                    required
                  >
                    <!-- ESTADOS -->
                    <option value="" disabled selected>
                      --Elige el Estado--
                    </option>
                  </select>
                </div>
                <!--SELECTOR MUNICIPIO CONDUCTOR-->
                <div class="administrator__form-group">
                  <label for="municipioConductor"
                    ><span class="administrator__form-label"
                      >Municipio:</span
                    ></label
                  >
                  <select
                    name="municipioConductor"
                    id="municipioConductor"
                    class="inputStyle"
                  >
                    <!-- MUNICIPIOS -->
                    <option value="" selected disabled>
                      --Elige el Municipio--
                    </option>
                  </select>
                </div>
              </div>
              <!-- CORREO ELECTRONICO -->
              <div class="administrator__form-group">
                <label for="emailConductor"
                  ><span class="administrator__form-label"
                    >Correo Electrónico:</span
                  ></label
                >
                <input
                  type="email"
                  class="administrator__form-input inputStyle"
                  name="emailConductor"
                  id="emailConductor"
                  placeholder="Ej: juan.perez@correo.com"
                  required
                />
              </div>
              <div class='administrator__form-group'>
                <label>
                  <span class='administrator__form-label'>Ubicacion:</span>
                  <textarea
                    class="administrator__form-input inputStyle descripcionUbicacion"
                    name="descripcionUbicacionConductor"
                    id='descripcionUbicacionConductor'
                    rows="3"
                    placeholder="Ej. Zona Educativa, ..."
                  ></textarea>
                </label>
              </div>
              <!-- ARCHIVOS CONDUCTOR -->
              <div class="administrator__form-group">
                <label for="fotoConductor"
                  ><span class="administrator__form-label"
                    >Adjuntar Foto del Conductor:</span
                  ></label
                >
                <div class="administrator__form-file">
                  <input
                    type="file"
                    class="administrator__form-input inputStyle"
                    name="foto"
                    id="fotoConductor"
                    accept="image/*"
                  />
                </div>
              </div>
            </div>
            <div class="administrator__form-group">
              <label for="documentosConductor"
                ><span class="administrator__form-label"
                  >Adjuntar Documentos:</span
                ></label
              >
              <div class="administrator__form-file">
                <input
                  type="file"
                  class="administrator__form-input inputStyle"
                  name="documentos[]"
                  id="documentosConductor"
                  accept="image/*,.pdf"
                  multiple
                />
              </div>
            </div>
            <div class="administrator__form-group">
              <label for="asignarVehiculoConductor"
                ><span class="administrator__form-label"
                  >¿Asignar Vehículo?</span
                ></label
              >
              <input
                type="checkbox"
                class="administrator__form-input"
                name="asignarVehiculoConductor"
                id="asignarVehiculoConductor"
              />
            </div>
            <div
              id="camposAsignarVehiculoConductor"
              class="administrator__conditional-fields"
              style="display: none"
            >
            <label for="matriculaVehiculoAsignado"
              ><span class="administrator__form-label"
                >Matrícula del Vehículo:</span
              ></label
            >
              <div class="input-group">
                <select name="prefijoIdentificadorVehiculo" id="prefijoIdentificadorVehiculo" class="inputStyle">
                  <option value="vin">Vin</option>
                  <option value="matricula">Matricula</option>
                </select>
                <input
                  type="text"
                  class="administrator__form-input inputStyle"
                  name="matriculaVehiculoAsignado"
                  id="matriculaVehiculoAsignado"
                  placeholder="Ej: ABC-123"
                />
              </div>
            </div>
            <input
              type="submit"
              name="registrarConductor"
              id="registrarConductor"
              class="administrator__form-submit"
              value="Registrar Conductor"
            />
          </form>

          <form
            class="administrator__modal-form"
            id="formPersonal"
            data-sectionmodal="personal"
            style="display: none"
            enctype="multipart/form-data"
          >
            <div class="administrator__basic-info">
              <div class="administrator__form-group">
                <label for="nombrePersonal"
                  ><span class="administrator__form-label"
                    >Nombres:</span
                  ></label
                >
                <input type="hidden" id="id_personal" name="id_personal" />
                <input
                  type="text"
                  class="administrator__form-input inputStyle"
                  name="nombrePersonal"
                  id="nombrePersonal"
                  placeholder="Ej: Ana María"
                  required
                />
              </div>
              <div class="administrator__form-group">
                <label for="apellidoPersonal"
                  ><span class="administrator__form-label"
                    >Apellidos:</span
                  ></label
                >
                <input
                  type="text"
                  class="administrator__form-input inputStyle"
                  name="apellidoPersonal"
                  id="apellidoPersonal"
                  placeholder="Ej: González López"
                  required
                />
              </div>
            </div>
            <div class="administrator__basic-info">
              <div class="administrator__form-group">
                <label for="cedulaPersonal"
                  ><span class="administrator__form-label">Cédula:</span></label
                >
                <div class="input-group">
                  <select
                    name="prefijoCedulaPersonal"
                    id="prefijoCedulaPersonal"
                    class="inputStyle"
                    style="width: auto"
                  >
                    <option value="V-">V-</option>
                    <option value="E-">E-</option>
                  </select>
                  <input
                    type="text"
                    class="administrator__form-input inputStyle"
                    name="cedulaPersonal"
                    id="cedulaPersonal"
                    placeholder="Ej: 12345678"
                    title="Ingrese de 7 a 8 dígitos"
                    required
                  />
                </div>
              </div>
              <div class="administrator__form-group">
                <label for="telefonoPersonal"
                  ><span class="administrator__form-label"
                    >Teléfono:</span
                  ></label
                >
                <input
                  type="tel"
                  class="administrator__form-input inputStyle"
                  name="telefonoPersonal"
                  id="telefonoPersonal"
                  placeholder="Ej: 0416-1234567"
                  title="Formato: 0XXX-XXXXXXX"
                  required
                />
              </div>
            </div>
            <!--GRUPO DE SELECTORES UBICACION PERSONAL-->
            <div class="administrator__basic-info">
              <!--SELECTOR ESTADO PERSONAL-->
              <div class="administrator__form-group">
                <label for="estadoSedePersonal"
                  ><span class="administrator__form-label">Estado:</span></label
                >
                <select
                  name="estadoSedePersonal"
                  id="estadoSedePersonal"
                  class="inputStyle"
                  required
                >
                  <!-- ESTADOS -->
                  <option value="" disabled selected>
                    --Elige el Estado--
                  </option>
                </select>
              </div>
              <!--SELECTOR MUNICIPIO PERSONAL-->
              <div class="administrator__form-group">
                <label for="municipioPersonal"
                  ><span class="administrator__form-label"
                    >Municipio:</span
                  ></label
                >
                <select
                  name="municipioPersonal"
                  id="municipioPersonal"
                  class="inputStyle"
                >
                  <!-- MUNICIPIOS -->
                  <option value="" selected disabled>
                    --Elige el Municipio--
                  </option>
                </select>
              </div>
            </div>
            <div class="administrator__form-group">
              <label for="emailPersonal"
                ><span class="administrator__form-label"
                  >Correo Electrónico:</span
                ></label
              >
              <input
                type="email"
                class="administrator__form-input inputStyle"
                name="emailPersonal"
                id="emailPersonal"
                placeholder="Ej: ana.gonzalez@correo.com"
                required
              />
            </div>
            <div class='administrator__form-group'>
              <label>
                <span class='administrator__form-label'>Ubicacion:</span>
                <textarea
                  class="administrator__form-input inputStyle descripcionUbicacion"
                  name="descripcionUbicacionPersonal"
                  id='descripcionUbicacionPersonal'
                  rows="3"
                  placeholder="Ej. Zona Educativa, ..."
                ></textarea>
              </label>
            </div>
            <div class="administrator__form-group">
              <label for="cargoPersonal"
                ><span class="administrator__form-label">Cargo:</span></label
              >
              <input
                type="text"
                class="administrator__form-input inputStyle"
                name="cargoPersonal"
                id="cargoPersonal"
                placeholder="Ej: Asistente Administrativo"
                required
              />
            </div>
            <div class="administrator__form-group">
              <label for="fotoPersonal"
                ><span class="administrator__form-label"
                  >Adjuntar Foto del Personal:</span
                ></label
              >
              <div class="administrator__form-file">
                <input
                  type="file"
                  class="administrator__form-input inputStyle"
                  name="foto"
                  id="fotoPersonal"
                  accept="image/*"
                />
              </div>
            </div>
            <div class="administrator__form-group">
              <label for="documentosPersonal"
                ><span class="administrator__form-label"
                  >Adjuntar Documentos:</span
                ></label
              >
              <div class="administrator__form-file">
                <input
                  type="file"
                  class="administrator__form-input inputStyle"
                  name="documentos[]"
                  id="documentosPersonal"
                  accept="image/*,.pdf"
                  multiple
                />
              </div>
            </div>
            <input
              type="submit"
              name="registrarPersonal"
              id="registrarPersonal"
              class="administrator__form-submit"
              value="Registrar Personal"
            />
          </form>

          <form
            class="administrator__modal-form"
            id="formMantenimientos"
            data-sectionmodal="mantenimientos"
            style="display: none"
            enctype="multipart/form-data"
          >
          <input type="hidden" id="id_mantenimiento" name="id_mantenimiento" />
            <div class="administrator__form-group">
              <label for="vehiculoMantenimiento"
                ><span class="administrator__form-label"
                  >Vehículo:</span
                ></label
              >
              <div class="input-group">
                <select name="prefijoIdentificadorVehiculo" id="prefijoIdentificadorVehiculo" class="inputStyle">
                  <option value="vin">Vin</option>
                  <option value="matricula">Matricula</option>
                </select>
                <input
                  type="text"
                  class="administrator__form-input inputStyle"
                  name="vehiculoMantenimiento"
                  id="vehiculoMantenimiento"
                  placeholder="Ingresa el identificador del vehiculo"
                  required
                />
              </div>
            </div>
            <div class="administrator__form-group">
              <label for="tipoMantenimiento"
                ><span class="administrator__form-label"
                  >Tipo de Mantenimiento:</span
                ></label
              >
              
              <select
                name="tipoMantenimiento"
                id="tipoMantenimiento"
                class="inputStyle"
                required
              >
                <option value="" disabled selected>
                  -- Seleccione Tipo --
                </option>
                <option value="Preventivo">Preventivo</option>
                <option value="Correctivo">Correctivo</option>
                <option value="Predictivo">Predictivo</option>
                <option value="Otro">Otro</option>
              </select>
            </div>
            <div class="administrator__basic-info">
              <div class="administrator__form-group">
                <label for="fechaMantenimiento"
                  ><span class="administrator__form-label">Fecha:</span></label
                >
                <input
                  type="date"
                  class="administrator__form-input inputStyle"
                  name="fechaMantenimiento"
                  id="fechaMantenimiento"
                  required
                />
              </div>
              <div class="administrator__form-group">
                <label for="costoMantenimiento"
                  ><span class="administrator__form-label"
                    >Costo (Bs.):</span
                  ></label
                >
                <input
                  type="number"
                  class="administrator__form-input inputStyle"
                  name="costoMantenimiento"
                  id="costoMantenimiento"
                  placeholder="Ej: 1500.50"
                  step="0.01"
                  min="0"
                />
              </div>
            </div>
            <div class="administrator__form-group">
              <label for="tallerMantenimiento"
                ><span class="administrator__form-label"
                  >Taller/Proveedor:</span
                ></label
              >
              <input
                type="text"
                class="administrator__form-input inputStyle"
                name="tallerMantenimiento"
                id="tallerMantenimiento"
                placeholder="Nombre del taller o proveedor"
              />
            </div>
            <div class="administrator__form-group">
              <label for="descripcionMantenimiento"
                ><span class="administrator__form-label"
                  >Descripción:</span
                ></label
              >
              <textarea
                class="administrator__form-input inputStyle"
                name="descripcionMantenimiento"
                id="descripcionMantenimiento"
                rows="3"
                placeholder="Detalles del mantenimiento realizado..."
              ></textarea>
            </div>
            <div class="administrator__form-group">
              <label for="archivosMantenimiento"
                ><span class="administrator__form-label"
                  >Adjuntar Facturas/Reportes:</span
                ></label
              >
              <div class="administrator__form-file">
                <input
                  type="file"
                  class="administrator__form-input inputStyle"
                  name="archivosMantenimiento[]"
                  id="archivosMantenimiento"
                  multiple
                  accept="image/*,.pdf,.doc,.docx"
                />
              </div>
            </div>
            <input
              type="submit"
              name="registrarMantenimiento"
              id="registrarMantenimiento"
              class="administrator__form-submit"
              value="Registrar Mantenimiento"
            />
          </form>

          <div class="administrator__search-container" style="display: none">
            <div class="administrator__search-content">
              <div class="administrator__search-header">
                <h3 id="searchTitle">
                  Buscar para <span id="searchActionText"></span>
                </h3>
              </div>
              <div class="administrator__search-body">
                <form
                  id="administratorSearchForm"
                  class="administrator__search-form"
                  method="POST"
                >
                  <div class="administrator__form-group">
                    <label
                      for="searchIdInput"
                      class="administrator__form-label"
                      id="searchIdLabel"
                      >ID del Elemento:</label
                    >
                    <div class="group-search">
                      <input
                        type="text"
                        id="searchIdInput"
                        name="searchIdInput"
                        class="inputStyle"
                        placeholder="Ingrese el ID o Matrícula"
                      />
                    </div>
                  </div>
                  <button
                    type="submit"
                    id="searchSubmitButton"
                    class="administrator__search-submit"
                  >
                    Buscar
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
