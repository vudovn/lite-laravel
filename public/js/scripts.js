/**
 * General Scripts
 * This file contains general scripts for the application
 */

document.addEventListener("DOMContentLoaded", function () {
  // Initialize tooltips
  var tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Initialize popovers
  var popoverTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="popover"]')
  );
  var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
  });

  // Auto-hide alerts after 5 seconds
  setTimeout(function () {
    var alerts = document.querySelectorAll(".alert");
    alerts.forEach(function (alert) {
      var bsAlert = new bootstrap.Alert(alert);
      bsAlert.close();
    });
  }, 5000);

  // Prevent multiple form submissions
  var forms = document.querySelectorAll("form");
  forms.forEach(function (form) {
    form.addEventListener("submit", function (e) {
      // Get the submit button
      var submitButton = form.querySelector('button[type="submit"]');
      if (submitButton) {
        // Disable the button and show loading state
        submitButton.disabled = true;
        var originalText = submitButton.innerHTML;
        submitButton.innerHTML =
          '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';

        // Re-enable after 10 seconds in case of network issues
        setTimeout(function () {
          submitButton.disabled = false;
          submitButton.innerHTML = originalText;
        }, 10000);
      }
    });
  });

  // Add smooth scrolling to all links
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();

      const targetId = this.getAttribute("href");
      if (targetId === "#") return;

      const targetElement = document.querySelector(targetId);
      if (targetElement) {
        targetElement.scrollIntoView({
          behavior: "smooth",
          block: "start",
        });
      }
    });
  });
});

/**
 * Shows a toast notification
 *
 * @param {string} message - The message to display
 * @param {string} type - The type of notification: success, error, warning, info
 * @param {string} title - Optional title for the notification
 */
function showToast(message, type = "info", title = "") {
  // Create the toast container if it doesn't exist
  let toastContainer = document.querySelector(".toast-container");
  if (!toastContainer) {
    toastContainer = document.createElement("div");
    toastContainer.className =
      "toast-container position-fixed bottom-0 end-0 p-3";
    document.body.appendChild(toastContainer);
  }

  // Map type to Bootstrap color class
  const typeClass = {
    success: "bg-success text-white",
    error: "bg-danger text-white",
    warning: "bg-warning",
    info: "bg-info",
  };

  // Create toast element
  const toastEl = document.createElement("div");
  toastEl.className = `toast ${typeClass[type] || "bg-light"}`;
  toastEl.setAttribute("role", "alert");
  toastEl.setAttribute("aria-live", "assertive");
  toastEl.setAttribute("aria-atomic", "true");

  let toastHtml = "";
  if (title) {
    toastHtml += `
            <div class="toast-header">
                <strong class="me-auto">${title}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
  }

  toastHtml += `
        <div class="toast-body">
            ${message}
            ${
              !title
                ? '<button type="button" class="btn-close ms-auto" data-bs-dismiss="toast" aria-label="Close"></button>'
                : ""
            }
        </div>
    `;

  toastEl.innerHTML = toastHtml;
  toastContainer.appendChild(toastEl);

  // Initialize and show the toast
  const toast = new bootstrap.Toast(toastEl);
  toast.show();

  // Remove the toast after it's hidden
  toastEl.addEventListener("hidden.bs.toast", function () {
    toastEl.remove();
  });
}
