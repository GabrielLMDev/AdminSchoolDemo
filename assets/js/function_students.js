document.addEventListener("DOMContentLoaded", (event) => {
    getGeneralAttendance();
    getFinalGrade();
    getActualGrades();
    getGradeGeneral();
});

async function getGeneralAttendance() {
    const url = '../../assets/php/getAttendance.php';

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const result = await response.json();
        console.log('Response Server:', result.status + " Actual Attendance");

        if (result.status === 'success') {
            let percent = parseFloat(result.percent_attendance);
            document.getElementById('attendance').innerHTML = percent.toFixed(1) + "%";
        } else {
            console.error('Error en la respuesta del servidor:', result);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function getActualGrades() {
    const url = '../../assets/php/getGrades.php';

    const data = {
        option: 1
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
        console.log('Response Server:', result.status + " Actual Grades");

        const table = document.getElementById('actual_grades_table');
        const tbody = table.querySelector('tbody');
        tbody.innerHTML = '';

        if (result.status === 'success') {
            const subjects = result.data;
            subjects.forEach(subject => {
                const row = document.createElement('tr');

                // Celda de materia
                const subjectName = document.createElement('th');
                subjectName.scope = "row";
                subjectName.textContent = subject.name;
                row.appendChild(subjectName);

                // Celdas de parciales
                const partial1 = document.createElement('td');
                partial1.textContent = subject.grades[1] || '';
                row.appendChild(partial1);

                const partial2 = document.createElement('td');
                partial2.textContent = subject.grades[2] || '';
                row.appendChild(partial2);

                const partial3 = document.createElement('td');
                partial3.textContent = subject.grades[3] || '';
                row.appendChild(partial3);

                // Celda de calificación final (promedio)
                const finalGrade = document.createElement('td');
                finalGrade.textContent = subject.final.toFixed(2);
                row.appendChild(finalGrade);

                // Celda de maestro
                const teacherName = document.createElement('td');
                teacherName.textContent = subject.teacher;
                row.appendChild(teacherName);

                // Celda de tipo
                const type = document.createElement('td');
                type.textContent = subject.type;
                row.appendChild(type);

                // Añadir fila al cuerpo de la tabla
                tbody.appendChild(row);
            });
        } else {
            console.error('Error en la respuesta del servidor:', result);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function getGradeGeneral() {
    const url = '../../assets/php/getGrades.php';

    const data = {
        option: 2,
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
        console.log('Response Server:', result.status + " General Grades");

        const table = document.getElementById('total_grades_table');
        const tbody = table.querySelector('tbody');
        tbody.innerHTML = '';

        if (result.status === 'success') {
            const subjects = result.data;
            subjects.forEach(subject => {
                const row = document.createElement('tr');

                // Celda de materia
                const subjectName = document.createElement('th');
                subjectName.scope = "row";
                subjectName.textContent = subject.name;
                row.appendChild(subjectName);

                // Celdas de parciales
                const partial1 = document.createElement('td');
                partial1.textContent = subject.grades[1] || '';
                row.appendChild(partial1);

                const partial2 = document.createElement('td');
                partial2.textContent = subject.grades[2] || '';
                row.appendChild(partial2);

                const partial3 = document.createElement('td');
                partial3.textContent = subject.grades[3] || '';
                row.appendChild(partial3);

                // Celda de calificación final (promedio)
                const finalGrade = document.createElement('td');
                finalGrade.textContent = subject.final.toFixed(2);
                row.appendChild(finalGrade);

                // Celda de maestro
                const teacherName = document.createElement('td');
                teacherName.textContent = subject.teacher;
                row.appendChild(teacherName);

                // Celda de tipo
                const type = document.createElement('td');
                type.textContent = subject.type;
                row.appendChild(type);

                // Añadir fila al cuerpo de la tabla
                tbody.appendChild(row);
            });
        } else {
            console.error('Error en la respuesta del servidor:', result);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function getFinalGrade() {
    const url = '../../assets/php/getGrades.php';

    const data = {
        option: 3,
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
            let grade_general = parseFloat(result.data);
            document.getElementById('grade_general').innerHTML = grade_general.toFixed(2);
        } else {

        }
    } catch (error) {
        console.error('Error:', error);
    }
}