window.addEventListener("DOMContentLoaded", () => {

    console.log("script start");

    let list_elements = document.getElementsByClassName("list-element");
    for (let i = 0; i < list_elements.length; i++) {
        let content = list_elements[i].textContent;
        list_elements[i].textContent = String(i+1) + '. ' + content;
    }

});