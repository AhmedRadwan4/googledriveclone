// Debounce function to limit the rate of function execution
const debounce = (func, delay) => {
    let timeoutId;
    return (...args) => {
        if (timeoutId) clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func(...args), delay);
    };
};

// Fetch search results with filter
const fetchResults = (query, filterType = "") => {
    // Check if the URL contains '/starred'
    const isStarred = window.location.pathname.includes("/starred");

    $.ajax({
        url: "search",
        type: "GET",
        data: {
            search: query,
            filterType: filterType,
            starred: isStarred, // Send 'starred' parameter based on the URL
        },
        success: (data) => $("#search_list").html(data.html),
    });
};

// Setup menu toggle
const setupMenu = (buttonId, menuRole) => {
    const menuButton = document.getElementById(buttonId);
    const menu = document.querySelector(`[role="${menuRole}"]`);

    const toggleMenu = () => {
        const isExpanded = menuButton.getAttribute("aria-expanded") === "true";
        menuButton.setAttribute("aria-expanded", !isExpanded);
        menu.classList.toggle("hidden");
    };

    menuButton.addEventListener("click", toggleMenu);

    document.addEventListener("click", (event) => {
        if (
            !menuButton.contains(event.target) &&
            !menu.contains(event.target)
        ) {
            menu.classList.add("hidden");
            menuButton.setAttribute("aria-expanded", "false");
        }
    });
};

$(document).ready(() => {
    // Cache jQuery selectors
    const $searchInput = $("#search");
    const $menuButton = $("#menu-button");

    // Fetch initial results
    fetchResults("");

    // Bind search input with debounce
    const debouncedFetchResults = debounce(
        (query) => fetchResults(query, $menuButton.attr("data-filter-type")),
        300
    );
    $searchInput.on("keyup", () => debouncedFetchResults($searchInput.val()));

    // Handle filter button click
    $(document).on("click", ".filter-button", function () {
        const filterType = $(this).attr("name");
        const filterText = $(this).text();
        $menuButton.text(`${filterText} \u23F7`);
        $menuButton.attr("data-filter-type", filterType);
        fetchResults($searchInput.val(), filterType);
        $(this).closest('[role="menu"]').addClass("hidden");
    });

    // Initialize menu
    setupMenu("menu-button", "menu");
});
