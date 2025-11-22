class MoviesPage {
    constructor() {
        this.template = null;
        this.cssLoaded = false;
    }

    async loadTemplate() {
        if (!this.template) {
            const response = await fetch('/pages/movies/Movies.html');
            this.template = await response.text();
        }
        return this.template;
    }

    async loadCSS() {
        if (!this.cssLoaded) {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.type = 'text/css';
            link.href = '/pages/movies/Movies.css';
            document.head.appendChild(link);
            this.cssLoaded = true;
        }
    }

    async render(data = null) {
        await this.loadCSS();
        const template = await this.loadTemplate();
        
        let html = template;
        
        if (data && Array.isArray(data)) {
            // Generamos las tarjetas de películas usando el componente MovieCard
            let movieCards = '';
            for (const pelicula of data) {
                // Cargar y renderizar cada tarjeta de película
                const movieCardComponent = new window.MovieCardComponent();
                const movieCardHtml = await movieCardComponent.render(pelicula);
                movieCards += movieCardHtml;
            }
            
            // Remplazamos el placeholder con las tarjetas de películas
            html = template.replace('{{movieGrid}}', movieCards);
        } else {
            html = template.replace('{{movieGrid}}', '<p>No se encontraron películas</p>');
        }
        
        return html;
    }
}

// Hacer que el componente esté disponible globalmente
window.MoviesPage = MoviesPage;