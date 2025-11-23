import adapter from "@sveltejs/adapter-static";

/** @type {import('@sveltejs/kit').Config} */
const config = {
  kit: {
    paths: {
      base: "",
    },
    adapter: adapter({
      fallback: "index.html",
      pages: "../public",
      assets: "../public",
    }),
  },
};

export default config;
