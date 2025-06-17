import "./bootstrap";
import Alpine from "alpinejs";
import BookingForm from "./bookingForm";

window.Alpine = Alpine;
Alpine.data("BookingForm", BookingForm);
Alpine.start();