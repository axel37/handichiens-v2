import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ['menuList'];

    connect() {
    }

    toggleMenu() {
        this.menuListTarget.classList.toggle("active");
    }
}