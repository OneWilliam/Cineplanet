import { error } from "@sveltejs/kit";
import { getMovieById } from "$lib/data/movies";

export const load = ({ params }) => {
  const { cine, func } = params;
  return {
    cine,
    func,
  };
};
