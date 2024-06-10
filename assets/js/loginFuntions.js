const studentPasswordElement = document.querySelector("#password");
document.getElementById('showPassword').addEventListener('change', function () {
    if (this.checked) {
        studentPasswordElement.type = 'text';
    } else {
        studentPasswordElement.type = 'password';
    }
});

document.getElementById('loginForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const studentNumberElement = document.querySelector("#numberControl");
    const url = './assets/php/User_Login.php';

    const data = {
        student_number: studentNumberElement.value,
        password: studentPasswordElement.value
    };

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const result = await response.json();
        console.log('Response Server:', result);
        if (result.status == 'success') {
            window.location.href = './views/student/index.html';
        } else {
            // Muestra el toast con la respuesta
            const toastContainer = document.getElementById('toastNotifi');
            const toast = document.createElement('div');
            toast.classList.add('toast', 'fade');
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');

            const toastHeader = `
                    <div class="toast-header">
                    <img src="assets/img/icons/GabrielLMDev.svg" class="bd-placeholder-img rounded me-2" width="20" height="20"/>
                        <strong class="me-auto">Server Response</strong>
                        <small>${new Date().toLocaleTimeString()}</small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">${result.message}</div>
                `;
            toast.innerHTML = toastHeader;

            toastContainer.appendChild(toast);
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
        }
    } catch (error) {
        console.error('Error:', error);
    }
});
