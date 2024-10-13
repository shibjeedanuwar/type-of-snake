document.addEventListener("DOMContentLoaded", function() {
    const initQuill = () => {
        const venEditor = document.getElementById("venomous_editor");
        const antiEditor = document.getElementById("antibiotic_editor");

        if (venEditor && antiEditor) {
            const quillOptions = {
                theme: "snow",
                modules: {
                    toolbar: [
                        ["bold", "italic", "underline", "strike"],
                        [{ "font": [] }],
                        [{ "align": [] }],
                        ["blockquote", "link"],
                        [{ "header": 1 }, { "header": 2 }],
                        [{ "list": "ordered" }, { "list": "bullet" }, { "list": "check" }],
                        [{ "size": ["small", false, "large", "huge"] }],
                        [{ "color": [] }, { "background": [] }],
                        ["clean"]
                    ]
                }
            };

            const venomousQuill = new Quill(venEditor, quillOptions);
            const antibioticQuill = new Quill(antiEditor, quillOptions);

            // Set Quill content with existing data
            venomousQuill.root.innerHTML = document.getElementById("venomous").value;
            antibioticQuill.root.innerHTML = document.getElementById("antibiotic").value;

            venomousQuill.on("text-change", function() {
                document.getElementById("venomous").value = venomousQuill.root.innerHTML;
            });

            antibioticQuill.on("text-change", function() {
                document.getElementById("antibiotic").value = antibioticQuill.root.innerHTML;
            });
        } else {
            console.error("Quill container elements not found.");
        }
    };

    // Call the init function to initialize Quill editors
    initQuill();
});