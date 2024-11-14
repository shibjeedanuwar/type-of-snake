document.addEventListener("DOMContentLoaded", function() {
    const initQuill = () => {
        const descriptionEditor = document.getElementById("description_editor");
        const dangerLevelEditor = document.getElementById("danger_level_editor");
        const temperamentEditor = document.getElementById("temperament_editor");
        const sizeRangeEditor = document.getElementById("size_range_editor");
        const habitatEditor = document.getElementById("habitat_editor");
        const lifespanEditor = document.getElementById("lifespan_editor");

        if (descriptionEditor && dangerLevelEditor && temperamentEditor && sizeRangeEditor && habitatEditor && lifespanEditor) {
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

            const descriptionQuill = new Quill(descriptionEditor, quillOptions);
            const dangerLevelQuill = new Quill(dangerLevelEditor, quillOptions);
            const temperamentQuill = new Quill(temperamentEditor, quillOptions);
            const sizeRangeQuill = new Quill(sizeRangeEditor, quillOptions);
            const habitatQuill = new Quill(habitatEditor, quillOptions);
            const lifespanQuill = new Quill(lifespanEditor, quillOptions);

            // Set Quill content with existing data
            descriptionQuill.root.innerHTML = document.getElementById("description").value;
            dangerLevelQuill.root.innerHTML = document.getElementById("danger_level").value;
            temperamentQuill.root.innerHTML = document.getElementById("temperament").value;
            sizeRangeQuill.root.innerHTML = document.getElementById("size_range").value;
            habitatQuill.root.innerHTML = document.getElementById("habitat").value;
            lifespanQuill.root.innerHTML = document.getElementById("lifespan").value;

            descriptionQuill.on("text-change", function() {
                document.getElementById("description").value = descriptionQuill.root.innerHTML;
            });

            dangerLevelQuill.on("text-change", function() {
                document.getElementById("danger_level").value = dangerLevelQuill.root.innerHTML;
            });

            temperamentQuill.on("text-change", function() {
                document.getElementById("temperament").value = temperamentQuill.root.innerHTML;
            });

            sizeRangeQuill.on("text-change", function() {
                document.getElementById("size_range").value = sizeRangeQuill.root.innerHTML;
            });

            habitatQuill.on("text-change", function() {
                document.getElementById("habitat").value = habitatQuill.root.innerHTML;
            });

            lifespanQuill.on("text-change", function() {
                document.getElementById("lifespan").value = lifespanQuill.root.innerHTML;
            });
        } else {
            console.error("Quill container elements not found.");
        }
    };

    // Call the init function to initialize Quill editors
    initQuill();
});