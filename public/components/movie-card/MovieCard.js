class MovieCardComponent {
    constructor() {
        this.template = null;
        this.cssLoaded = false;
    }

    async loadTemplate() {
        if (!this.template) {
            const response = await fetch('/components/movie-card/MovieCard.html');
            this.template = await response.text();
        }
        return this.template;
    }

    async loadCSS() {
        if (!this.cssLoaded) {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.type = 'text/css';
            link.href = '/components/movie-card/MovieCard.css';
            document.head.appendChild(link);
            this.cssLoaded = true;
        }
    }

    async render(data) {
        await this.loadCSS();
        const template = await this.loadTemplate();

        // Remplazamos los placeholders con los datos de la película
        return template
            .replace('{{imagen}}', data.images && data.images[0] ? data.images[0] : '/img/placeholder-movie.jpg')
            .replace('{{nombre}}', data.nombre || 'Nombre no disponible')
            .replace('{{duracion}}', data.duracion || 'Duración no disponible')
            .replace('{{id}}', data.pelicula_id || 0);
    }
}

// Hacer que el componente esté disponible globalmente
window.MovieCardComponent = MovieCardComponent;