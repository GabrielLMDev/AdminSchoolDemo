
document.addEventListener("DOMContentLoaded", getGradeGeneral);

async function getGradeGeneral() {
    const url = '../../assets/php/getGrades.php';

    const data = {
        option: 2,
        enrollment_id: 1
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
            let grade_general = parseFloat(result.grade_general);
            document.getElementById('grade_general').innerHTML = grade_general.toFixed(2);
        } else {


        }
    } catch (error) {
        console.error('Error:', error);
    }
}