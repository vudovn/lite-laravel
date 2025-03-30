/**
 * Admin Dashboard JavaScript Functions
 */

// Document ready
$(document).ready(function () {
  // Toggle sidebar
  $("#sidebarCollapse").on("click", function () {
    $("#sidebar").toggleClass("active");
  });

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

  // Confirmation for delete actions
  $(".delete-confirm").on("click", function (e) {
    if (
      !confirm(
        "Are you sure you want to delete this item? This action cannot be undone."
      )
    ) {
      e.preventDefault();
    }
  });

  // Toggle password visibility
  $(".toggle-password").on("click", function () {
    const input = $($(this).attr("toggle"));
    const type = input.attr("type") === "password" ? "text" : "password";
    input.attr("type", type);
    $(this).toggleClass("fa-eye fa-eye-slash");
  });

  // Auto-hide alerts after 5 seconds
  setTimeout(function () {
    $(".alert-dismissible").alert("close");
  }, 5000);

  // Animate stats counters
  $(".counter").each(function () {
    const $this = $(this);
    const countTo = parseInt($this.text().replace(/,/g, ""));

    $({ countNum: 0 }).animate(
      {
        countNum: countTo,
      },
      {
        duration: 1000,
        easing: "swing",
        step: function () {
          $this.text(Math.floor(this.countNum).toLocaleString("en"));
        },
        complete: function () {
          $this.text(this.countNum.toLocaleString("en"));
        },
      }
    );
  });

  // Chart.js initialization (if available)
  if (typeof Chart !== "undefined" && $("#salesChart").length) {
    const ctx = document.getElementById("salesChart").getContext("2d");
    new Chart(ctx, {
      type: "line",
      data: {
        labels: [
          "Jan",
          "Feb",
          "Mar",
          "Apr",
          "May",
          "Jun",
          "Jul",
          "Aug",
          "Sep",
          "Oct",
          "Nov",
          "Dec",
        ],
        datasets: [
          {
            label: "Sales",
            data: [12, 19, 3, 5, 2, 3, 10, 15, 8, 12, 17, 20],
            backgroundColor: "rgba(78, 115, 223, 0.2)",
            borderColor: "rgba(78, 115, 223, 1)",
            borderWidth: 2,
            pointBackgroundColor: "rgba(78, 115, 223, 1)",
            pointBorderColor: "#fff",
            pointBorderWidth: 2,
            pointRadius: 4,
            tension: 0.3,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    });
  }

  // DataTables initialization (if available)
  if ($.fn.DataTable && $(".datatable").length) {
    $(".datatable").DataTable({
      responsive: true,
      language: {
        search: "_INPUT_",
        searchPlaceholder: "Search...",
      },
    });
  }

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

// AJAX form submission
function submitAjaxForm(formId, successCallback = null, errorCallback = null) {
  const form = $(formId);

  if (form.length) {
    form.on("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(this);

      $.ajax({
        url: form.attr("action"),
        type: form.attr("method"),
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
          if (typeof successCallback === "function") {
            successCallback(response);
          } else {
            showNotification("Form submitted successfully!");
          }
        },
        error: function (xhr) {
          if (typeof errorCallback === "function") {
            errorCallback(xhr);
          } else {
            showNotification(
              "Error submitting form. Please try again.",
              "error"
            );
          }
        },
      });
    });
  }
}
