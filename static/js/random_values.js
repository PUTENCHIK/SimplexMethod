window.addEventListener("DOMContentLoaded", () => {

    const min = -10, max = 10, sign_amount = 1;

    let btn_random_values = document.getElementsByClassName("random-values")[0];

    btn_random_values.addEventListener("click", () => {
        let inputs = document.getElementsByTagName("input");

        for (let i = 0; i < inputs.length; i++) {
            let value = min + (max - min) * Math.random();
            let tens = Math.pow(10, sign_amount);
            inputs[i].value = String(Math.round(value * tens) / tens);
        }
    });
});