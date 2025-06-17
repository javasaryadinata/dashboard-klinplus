import "./bootstrap";
import Alpine from "alpinejs";
import BookingForm from "./BookingForm";

window.Alpine = Alpine;
Alpine.data("BookingForm", BookingForm);
Alpine.start();