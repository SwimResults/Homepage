function changeCss () {
    const bodyElement = document.querySelector("body");
    if (this.scrollY > 200) {
        bodyElement.classList.add("scrolled")
    } else {
        bodyElement.classList.remove("scrolled")
    }
}

window.addEventListener("scroll", changeCss , false);