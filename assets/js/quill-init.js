document.addEventListener("DOMContentLoaded", function() {
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

    const venomousQuill = new Quill("#venomous_editor", quillOptions);
    const antibioticQuill = new Quill("#antibiotic_editor", quillOptions);

    // Set Quill content with existing data
    venomousQuill.root.innerHTML = document.getElementById("venomous").value;
    antibioticQuill.root.innerHTML = document.getElementById("antibiotic").value;

    venomousQuill.on("text-change", function() {
        document.getElementById("venomous").value = venomousQuill.root.innerHTML;
    });

    antibioticQuill.on("text-change", function() {
        document.getElementById("antibiotic").value = antibioticQuill.root.innerHTML;
    });
});