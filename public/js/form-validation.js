document.addEventListener("DOMContentLoaded", function () {
    const forms = document.querySelectorAll(
        "[data-reservation-form], [data-catering-form], [data-contact-form]",
    );

    forms.forEach(function (form) {
        // AQL-60: Use inline feedback instead of browser popups.
        form.noValidate = true;

        const fields = form.querySelectorAll(
            'input:not([type="hidden"]):not([type="submit"]):not([name="website"]), select, textarea',
        );

        function getFeedback(field) {
            let feedback = field.nextElementSibling;

            if (!feedback || !feedback.classList.contains("invalid-feedback")) {
                feedback = document.createElement("div");
                feedback.className = "invalid-feedback";
                feedback.dataset.clientValidation = "true";
                field.insertAdjacentElement("afterend", feedback);
            }

            if (!feedback.id) {
                feedback.id = field.id
                    ? field.id + "-client-error"
                    : field.name + "-client-error";
            }

            // AQL-70: Announce dynamically updated errors.
            feedback.setAttribute("role", "alert");
            feedback.setAttribute("aria-live", "assertive");

            return feedback;
        }

        function addDescription(field, feedbackId) {
            const ids = (field.getAttribute("aria-describedby") || "")
                .split(/\s+/)
                .filter(Boolean);

            if (!ids.includes(feedbackId)) {
                ids.push(feedbackId);
            }

            field.setAttribute("aria-describedby", ids.join(" "));
        }

        function removeDescription(field, feedbackId) {
            const ids = (field.getAttribute("aria-describedby") || "")
                .split(/\s+/)
                .filter((id) => id && id !== feedbackId);

            if (ids.length) {
                field.setAttribute("aria-describedby", ids.join(" "));
            } else {
                field.removeAttribute("aria-describedby");
            }
        }

        function validateField(field) {
            if (field.disabled || !field.name) {
                return true;
            }

            const valid = field.checkValidity();
            const feedback = getFeedback(field);
            const serverError = feedback.dataset.serverError === "true";

            if (!valid) {
                field.classList.add("is-invalid");
                field.classList.remove("is-valid");

                if (!serverError) {
                    feedback.textContent = field.validationMessage;
                }

                feedback.style.display = "block";

                field.setAttribute("aria-invalid", "true");
                addDescription(field, feedback.id);

                return false;
            }

            if (serverError) {
                // Keep the Laravel error until the field is edited.
                field.classList.add("is-invalid");
                field.setAttribute("aria-invalid", "true");
                addDescription(field, feedback.id);

                return false;
            }

            field.classList.remove("is-invalid");

            if (field.value.trim() !== "") {
                field.classList.add("is-valid");
            } else {
                field.classList.remove("is-valid");
            }

            feedback.textContent = "";
            feedback.style.display = "none";

            field.removeAttribute("aria-invalid");
            removeDescription(field, feedback.id);

            return true;
        }

        fields.forEach(function (field) {
            const feedback = field.nextElementSibling;

            if (
                feedback &&
                feedback.classList.contains("invalid-feedback") &&
                field.classList.contains("is-invalid")
            ) {
                feedback.dataset.serverError = "true";
                feedback.setAttribute("role", "alert");
                feedback.setAttribute("aria-live", "assertive");

                addDescription(field, feedback.id);
                field.setAttribute("aria-invalid", "true");
            }

            field.addEventListener("blur", function () {
                validateField(field);
            });

            function handleChange() {
                const feedback = getFeedback(field);

                if (feedback.dataset.serverError === "true") {
                    delete feedback.dataset.serverError;
                    feedback.textContent = "";
                }

                validateField(field);
            }

            field.addEventListener("input", handleChange);
            field.addEventListener("change", handleChange);
        });

        form.addEventListener("submit", function (event) {
            let firstInvalid = null;

            fields.forEach(function (field) {
                if (!validateField(field) && !firstInvalid) {
                    firstInvalid = field;
                }
            });

            if (firstInvalid) {
                event.preventDefault();
                firstInvalid.focus();
            }
        });
    });
});
