function showPopup(message) {
    const popupOverlay = document.getElementById("popupOverlay");
    const popupMessage = document.getElementById("popupMessage");

    if (popupOverlay && popupMessage) {
        popupMessage.textContent = message;
        popupOverlay.style.display = "flex";
    } else {
        alert(message);
    }
}

function closePopup() {
    const popupOverlay = document.getElementById("popupOverlay");
    if (popupOverlay) {
        popupOverlay.style.display = "none";
    }
}

function attachAjaxForm(formId) {
    const form = document.getElementById(formId);

    if (!form) {
        return;
    }

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch(form.action, {
            method: "POST",
            body: formData
        })
        .then(async response => {
            const text = await response.text();

            if (!response.ok) {
                throw new Error(text || ("Server error: " + response.status));
            }

            return text;
        })
        .then(data => {
            showPopup(data);
        })
        .catch(error => {
            console.error("Error:", error);
            showPopup(error.message || "Submission failed.");
        });
    });
}

document.addEventListener("DOMContentLoaded", function () {
    attachAjaxForm("addForm");
    attachAjaxForm("deleteStudentForm");
    attachAjaxForm("deleteRegistrationForm");
    attachAjaxForm("deleteAssessmentForm");
    attachAjaxForm("deleteTotalClicksForm");
    attachAjaxForm("deleteWeeklyClicksForm");
    attachAjaxForm("deleteActivityForm");
    attachAjaxForm("updateForm");
    attachAjaxForm("summaryForm");
});