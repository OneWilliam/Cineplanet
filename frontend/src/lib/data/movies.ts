import type { Pelicula } from "../types";

export const mockMovies: Pelicula[] = [
  {
    id: "HO00002474",
    titulo: "Chavin de Huantar El Rescate del Siglo",
    classificacion: "+14",
    duracion: "1hrs 35min",
    generos: ["Acción"],
    sinopsis:
      "En una operación de rescate sin precedentes, comandos de élite se infiltran en una embajada sitiada por terroristas para liberar a decenas de rehenes, enfrentando un desafío que pondrá a prueba su valentía y humanidad.",
    lenguaje: "DOBLADA",
    formatos: ["REGULAR", "2D", "PRIME"],
    poster: {
      id: "poster-HO00002474",
      url: "https://cdn.apis.cineplanet.com.pe/CDN/media/entity/get/FilmPosterGraphic/HO00002474?referenceScheme=HeadOffice&allowPlaceHolder=true",
      alt: "Chavin de Huantar El Rescate del Siglo",
    },
    trailerUrl: "rescate",
    diponibilidad: true,
    releaseDate: "2025-01-01",
  },
];

export function getMovies(): Pelicula[] {
  return mockMovies.map((m) => ({ ...m }));
}

export function getMovieById(id: string): Pelicula | null {
  return mockMovies.find((m) => m.id === id) ?? null;
}
