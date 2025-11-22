<form
    hx-get="/buscar_peliculas.php"
    hx-target="#peliculas-lista"
    hx-trigger="submit, keyup delay:500ms from:#search-input"
    hx-indicator="#search-spinner"
    class="search-form"
>
    <div class="search-container">
        <input
            type="text"
            id="search-input"
            name="q"
            placeholder="Buscar películas..."
            class="search-input"
        >
        <div id="search-spinner" class="htmx-indicator spinner">
            <i class="fas fa-spinner fa-spin"></i>
        </div>
    </div>
</form>