import { error } from "@sveltejs/kit";
import { getMovieById } from "$lib/data/movies";

export const load = ({ params }) => {
  const { slug } = params;
  const pelicula = getMovieById(slug);

  if (!pelicula) {
    throw error(404, "Película no encontrada");
  }
  return {
    pelicula,
  };
};
