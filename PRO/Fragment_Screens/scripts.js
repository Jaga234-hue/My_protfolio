  function startTyping() {
    let element = document.querySelector("#element");
    if (element) {
      element.innerHTML = ""; // reset so it types fresh
      new Typed("#element", {
        strings: [
          "Data Scientist",
          "Web Developer",
          "Machine Learning Enthusiast."
        ],
        typeSpeed: 50,
        backSpeed: 30,
        backDelay: 1000,
        loop: true
      });
    }
  }

  const links = document.querySelectorAll("nav ul li a");
  const content = document.getElementById("content");

  links.forEach(link => {
    link.addEventListener("click", function (e) {
      e.preventDefault(); // stop full page reload
      const file = this.getAttribute("data-file");

      fetch(file)
        .then(res => res.text())
        .then(data => {
          content.innerHTML = data; // inject file content inside <main>
          if (file.includes("home") || file.includes("index")) {
            // only restart typing if it's Home page
            startTyping();
          }
        })
        .catch(() => {
          content.innerHTML = "<p>Error loading page.</p>";
        });
    });
  });

  // Run typing effect once when page first loads
  document.addEventListener("DOMContentLoaded", startTyping);