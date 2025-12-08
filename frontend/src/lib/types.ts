export type PeliculaFormato =
  | "REGULAR"
  | "2D"
  | "3D"
  | "IMAX"
  | "PRIME"
  | string;

export type PeliculaIdioma = "DOBLADA" | "SUBTITULADA" | "ORIGINAL" | string;

export interface PeliculaImagen {
  id: string | number | null;
  url: string;
  alt?: string;
  width?: number;
  height?: number;
}

export interface PeliculaMinimal {
  id: string;
  titulo: string;
  poster?: PeliculaImagen;
  generos?: string[];
  duracion?: string;
  classificacion?: string;
}

export interface Pelicula {
  id: string;

  titulo: string;
  slug?: string;

  classificacion?: string;
  duracion?: string;
  sinopsis?: string;
  generos?: string[];
  director?: string;
  actores?: string[];

  poster?: PeliculaImagen;
  posterPlaceholder?: PeliculaImagen;
  trailerUrl?: string;

  lenguaje?: PeliculaIdioma;
  formatos?: PeliculaFormato[];
  diponibilidad?: boolean;

  releaseDate?: string;
  createdAt?: string;
  updatedAt?: string;

  [key: string]: unknown;
}

export type PeliculaMap = Record<string, Pelicula>;

export interface PagesPeliculas {
  items: PeliculaMinimal[];
  total: number;
  page: number;
  pageSize: number;
}

export interface PeliculaDetalle {
  pelicula: Pelicula | null;
}

export interface funcion {
  id: string;
  hora: string;
  lleno?: boolean;
  sitiosDisponibles?: number;
}

export interface detallesSesion {
  id: string;
  // "2D", "XTREME", "PRIME", "3D"
  dimension: PeliculaFormato | string;
  cine?: string;
  // "DOBLADA" | "SUBTITULADA"
  idioma?: PeliculaIdioma | string;
  funciones: funcion[];
}

export interface cinemaFunciones {
  id: string;
  nombre: string;
  ciudad?: string;
  distancia?: string;
  sesiones: detallesSesion[];
}

export type CinemaFuncionesPelicula = Record<string, cinemaFunciones[]>;
