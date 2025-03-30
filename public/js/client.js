/**
 * Client Website JavaScript Functions
 */

// Document ready
$(document).ready(function () {
  // Initialize tooltips
  const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
  tooltips.forEach((tooltip) => {
    new bootstrap.Tooltip(tooltip);
  });

  // Initialize popovers
  const popovers = document.querySelectorAll('[data-bs-toggle="popover"]');
  popovers.forEach((popover) => {
    new bootstrap.Popover(popover);
  });

  // Smooth scrolling for anchor links
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();

      const target = document.querySelector(this.getAttribute("href"));
      if (target) {
        window.scrollTo({
          top: target.offsetTop - 80,
          behavior: "smooth",
        });
      }
    });
  });

  // Add animation classes to elements when they come into view
  const animatedElements = document.querySelectorAll(".animate-on-scroll");

  if (animatedElements.length > 0) {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("animate-fade-in");
            observer.unobserve(entry.target);
          }
        });
      },
      {
        threshold: 0.1,
      }
    );

    animatedElements.forEach((element) => {
      observer.observe(element);
    });
  }

  // Toggle mobile navigation
  $(".navbar-toggler").on("click", function () {
    $(".navbar-collapse").toggleClass("show");
  });

  // Auto-hide alerts after 5 seconds
  setTimeout(function () {
    $(".alert-dismissible").alert("close");
  }, 5000);

  // Form validation
  const forms = document.querySelectorAll(".needs-validation");
  Array.from(forms).forEach((form) => {
    form.addEventListener(
      "submit",
      (event) => {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add("was-validated");
      },
      false
    );
  });

  // Back to top button
  const backToTopButton = document.querySelector(".back-to-top");

  if (backToTopButton) {
    window.addEventListener("scroll", () => {
      if (window.pageYOffset > 300) {
        backToTopButton.classList.add("show");
      } else {
        backToTopButton.classList.remove("show");
      }
    });

    backToTopButton.addEventListener("click", () => {
      window.scrollTo({
        top: 0,
        behavior: "smooth",
      });
    });
  }

  // Image gallery lightbox (if available)
  if (typeof GLightbox !== "undefined") {
    GLightbox({
      selector: ".glightbox",
      touchNavigation: true,
      loop: true,
      autoplayVideos: true,
    });
  }

  // Initialize carousels with autoplay
  const carousel = document.querySelector("#heroCarousel");
  if (carousel) {
    const carouselInstance = new bootstrap.Carousel(carousel, {
      interval: 5000,
      wrap: true,
    });
  }
});

// Toastify notification function
function showNotification(message, type = "success") {
  let background;

  switch (type) {
    case "success":
      background = "linear-gradient(to right, #00b09b, #96c93d)";
      break;
    case "error":
      background = "linear-gradient(to right, #ff5f6d, #ffc371)";
      break;
    case "warning":
      background = "linear-gradient(to right, #f6d365, #fda085)";
      break;
    case "info":
      background = "linear-gradient(to right, #2193b0, #6dd5ed)";
      break;
    default:
      background = "linear-gradient(to right, #00b09b, #96c93d)";
  }

  Toastify({
    text: message,
    duration: 3000,
    close: true,
    gravity: "top",
    position: "right",
    backgroundColor: background,
    stopOnFocus: true,
  }).showToast();
}

// Contact form AJAX submission
function submitContactForm() {
  const form = document.getElementById("contactForm");

  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();

      if (!form.checkValidity()) {
        form.classList.add("was-validated");
        return;
      }

      const formData = new FormData(this);
      const submitButton = form.querySelector('button[type="submit"]');
      const originalText = submitButton.innerHTML;

      submitButton.disabled = true;
      submitButton.innerHTML = "Sending...";

      fetch(form.action, {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            showNotification(
              "Message sent successfully! We'll get back to you soon.",
              "success"
            );
            form.reset();
            form.classList.remove("was-validated");
          } else {
            showNotification(
              data.message || "Something went wrong. Please try again.",
              "error"
            );
          }
        })
        .catch((error) => {
          showNotification("Error sending message. Please try again.", "error");
        })
        .finally(() => {
          submitButton.disabled = false;
          submitButton.innerHTML = originalText;
        });
    });
  }
}

// Initialize contact form submission
submitContactForm();
