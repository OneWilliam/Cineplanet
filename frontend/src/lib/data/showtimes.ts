import type {
  cinemaFunciones,
  CinemaFuncionesPelicula,
  funcion,
} from "$lib/types";

function ST(
  id: string,
  hora: string,
  sitiosDisponibles?: number,
  lleno?: boolean,
): funcion {
  return {
    id,
    hora,
    sitiosDisponibles,
    lleno,
  };
}

export const mockShowtimesByMovie: CinemaFuncionesPelicula = {
  HO00002474: [
    {
      id: "cp-alcazar",
      nombre: "CP Alcazar",
      ciudad: "Lima",
      distancia: "Opción más cercana",
      sesiones: [
        {
          id: "alcazar-2d-regular-sub",
          dimension: "2D",
          cine: "REGULAR",
          idioma: "SUBTITULADA",
          funciones: [
            ST("st-1367", "17:50", 24),
            ST("st-1368", "20:10", 28),
            ST("st-1369", "22:30", 7),
          ],
        },
      ],
    },
    {
      id: "cp-arequipa-mall-plaza",
      nombre: "CP Arequipa Mall Plaza",
      ciudad: "Arequipa",
      distancia: "Opción más cercana",
      sesiones: [
        {
          id: "areq-2d-regular-dob",
          dimension: "2D",
          cine: "REGULAR",
          idioma: "DOBLADA",
          funciones: [
            ST("st-1370", "16:50", 0, true),
            ST("st-1371", "17:50", 32),
            ST("st-1372", "18:10", 18),
            ST("st-1373", "19:10", 14),
            ST("st-1374", "20:10", 11),
            ST("st-1375", "20:30", 9),
            ST("st-1376", "21:30", 6),
            ST("st-1377", "22:30", 2),
          ],
        },
      ],
    },
    {
      id: "cp-arequipa-paseo-central",
      nombre: "CP Arequipa Paseo Central",
      ciudad: "Arequipa",
      distancia: "Opción más cercana",
      sesiones: [
        {
          id: "areq-paseo-2d-reg-dob",
          dimension: "2D",
          cine: "REGULAR",
          idioma: "DOBLADA",
          funciones: [
            ST("st-1378", "17:50", 10),
            ST("st-1379", "20:10", 16),
            ST("st-1380", "21:30", 7),
            ST("st-1381", "22:30", 3),
          ],
        },
      ],
    },
    {
      id: "cp-brasil",
      nombre: "CP Brasil",
      ciudad: "Lima",
      distancia: "Opción más cercana",
      sesiones: [
        {
          id: "brasil-2d-reg-dob",
          dimension: "2D",
          cine: "REGULAR",
          idioma: "DOBLADA",
          funciones: [
            ST("st-1387", "16:50", 0, true),
            ST("st-1388", "17:50", 22),
            ST("st-1389", "19:10", 20),
            ST("st-1390", "20:10", 12),
          ],
        },
        {
          id: "brasil-2d-reg-sub",
          dimension: "2D",
          cine: "REGULAR",
          idioma: "SUBTITULADA",
          funciones: [ST("st-1391", "21:30", 15), ST("st-1392", "22:30", 4)],
        },
      ],
    },
    {
      id: "cp-cusco",
      nombre: "CP Cusco",
      ciudad: "Cusco",
      distancia: "Opción más cercana",
      sesiones: [
        {
          id: "cusco-2d-reg-dob",
          dimension: "2D",
          cine: "REGULAR",
          idioma: "DOBLADA",
          funciones: [
            ST("st-1435", "16:50", 0, true),
            ST("st-1436", "18:10", 19),
            ST("st-1437", "19:10", 10),
            ST("st-1438", "20:30", 6),
            ST("st-1439", "21:30", 4),
          ],
        },
        {
          id: "cusco-2d-xtreme-dob",
          dimension: "2D",
          cine: "XTREME",
          idioma: "DOBLADA",
          funciones: [
            ST("st-1440", "17:50", 33),
            ST("st-1441", "20:10", 22),
            ST("st-1442", "22:30", 2),
          ],
        },
      ],
    },
    {
      id: "cp-piura",
      nombre: "CP Piura",
      ciudad: "Piura",
      distancia: "Opción más cercana",
      sesiones: [
        {
          id: "piura-2d-reg-dob",
          dimension: "2D",
          cine: "REGULAR",
          idioma: "DOBLADA",
          funciones: [
            ST("st-1501", "16:50", 0, true),
            ST("st-1502", "17:50", 20),
            ST("st-1503", "19:10", 14),
            ST("st-1504", "20:10", 8),
            ST("st-1505", "21:30", 6),
            ST("st-1506", "22:30", 2),
          ],
        },
      ],
    },
    {
      id: "cp-trujillo-centro",
      nombre: "CP Trujillo Centro",
      ciudad: "Trujillo",
      distancia: "Opción más cercana",
      sesiones: [
        {
          id: "trujillo-2d-reg-dob",
          dimension: "2D",
          cine: "REGULAR",
          idioma: "DOBLADA",
          funciones: [
            ST("st-1620", "17:50", 12),
            ST("st-1621", "19:10", 9),
            ST("st-1622", "20:10", 9),
            ST("st-1623", "21:30", 4),
            ST("st-1624", "22:30", 1),
          ],
        },
      ],
    },
  ],
};

export function getShowtimesForMovie(movieId: string): cinemaFunciones[] {
  return mockShowtimesByMovie[movieId] ?? [];
}

export function getCitiesForMovie(movieId: string): string[] {
  const cinemas = getShowtimesForMovie(movieId);
  const cities: Set<string> = new Set();
  for (const c of cinemas) {
    if (c.ciudad) cities.add(c.ciudad);
  }
  return Array.from(cities).sort();
}

export function getCinemasForMovieAndCity(
  movieId: string,
  city?: string,
): cinemaFunciones[] {
  const cinemas = getShowtimesForMovie(movieId);
  if (!city || city === "Dónde estás") return cinemas;
  return cinemas.filter(
    (c) => (c.ciudad ?? "").toLowerCase() === city.toLowerCase(),
  );
}

/**
 * [
 *   { value: '2025-12-07T00:00:00', label: 'Hoy Domingo 7' },
 *   { value: '2025-12-08T00:00:00', label: 'Mañana Lunes 8' },
 *   ...
 * ]
 */
export function getNextNDatesForSelect(
  nDays = 4,
): { value: string; label: string }[] {
  const now = new Date();
  const list: { value: string; label: string }[] = [];
  for (let i = 0; i < nDays; i++) {
    const d = new Date(now);
    d.setDate(now.getDate() + i);
    const iso =
      new Date(d.getFullYear(), d.getMonth(), d.getDate())
        .toISOString()
        .split("T")[0] + "T00:00:00";
    const weekday = d.toLocaleDateString("es-PE", { weekday: "long" });
    const dayNumber = d.getDate();
    const prefix =
      i === 0
        ? "Hoy"
        : i === 1
          ? "Mañana"
          : weekday[0].toUpperCase() + weekday.slice(1);
    list.push({
      value: iso,
      label: `${prefix} ${capitalizeFirst(weekday)} ${dayNumber}`,
    });
  }
  return list;
}

function capitalizeFirst(s: string) {
  if (!s) return s;
  return s[0].toUpperCase() + s.slice(1);
}

export function getCinemaById(
  movieId: string,
  cinemaId: string,
): cinemaFunciones | null {
  const cinemas = getShowtimesForMovie(movieId);
  return cinemas.find((c) => c.id === cinemaId) ?? null;
}

export function findShowtime(
  movieId: string,
  cinemaId: string,
  showtimeId: string,
): funcion | null {
  const cinema = getCinemaById(movieId, cinemaId);
  if (!cinema) return null;
  for (const s of cinema.sesiones) {
    const st = s.funciones.find((x) => x.id === showtimeId);
    if (st) return st;
  }
  return null;
}
