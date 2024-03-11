export default {
    install: (app, options, debounce = null) => {
        // create a helpers object and inject methods into object
        app.config.globalProperties.$helpers = {
            // Debounce method
            debounce: (func) => {
                // clearTimeout is used when you want to prevent the callback from getting called before that would happen.
                clearTimeout(debounce);
                // Stores timeout id  and delays 400 millisecond
                debounce = setTimeout(() => {
                    // call function
                    func();
                }, 400);
            },
            numberOnly: (evt) => {
                var theEvent = evt || window.event;

                // Handle paste
                if (theEvent.type === "paste") {
                    key = event.clipboardData.getData("text/plain");
                } else {
                    // Handle key press
                    var key = theEvent.key;
                    var code = theEvent.which
                        ? theEvent.which
                        : theEvent.keyCode;
                    // key = String.fromCharCode(key);
                }
                var regex = /[0-9]|\./;
                if (
                    !regex.test(key) &&
                    ![37, 39, 9, 8, 16, 46, 38, 40].includes(code)
                ) {
                    theEvent.returnValue = false;
                    if (theEvent.preventDefault) theEvent.preventDefault();
                    evt.target.value = "";
                }
            },
            // format date yyyy-mm-dd to dd-mm-yyyy
            formatDate: (date) => {
                return date.split("-").reverse().join("-");
            },
            convertStringToJSDate: (dateString) => {
                if (dateString.includes("T"))
                    dateString = dateString.split("T")[0];
                let [year, month, day] = dateString.split("-");
                return new Date(year, month - 1, day);
            },
            convertDateToString: (originalDateStr) => {
                if(!originalDateStr) return ""
                const originalDate = new Date(originalDateStr);

                const year = originalDate.getFullYear();
                const month = String(originalDate.getMonth() + 1).padStart(
                    2,
                    "0"
                );
                const day = String(originalDate.getDate()).padStart(2, "0");

                const formattedDate = `${year}-${month}-${day}`;
                return formattedDate;
            }

        }
    }
}
