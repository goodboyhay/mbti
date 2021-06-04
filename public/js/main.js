function ConfirmBox(message) {
    if (window.confirm(message)) {
        return true;
    }
    return false;
}