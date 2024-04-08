var LESSONS = [];
var SELECTEDLESSON = 0;
var SELECTEDCURR = 'POUNDS';

function loadAndHandleJSON(jsonFile, callback) {
    fetch(jsonFile)
        .then(response => response.json())
        .then(data => callback(null, data))
        .catch(error => callback(error, null));
}

function setUpLessons(error, lessons) {
    if (error) {
        console.error('Error loading JSON file:', error);
    } else {
        console.log('JSON file loaded successfully:', lessons);
        // Get the reference to the <ul> element
        var lessonsList = document.getElementById("lessonsList");

        lessons.forEach(function(lesson) {
            console.log(lesson.id, lesson.title);
            LESSONS[lesson.id] = lesson;
            // Create a new <li> element
            var li = document.createElement("li");
            // Set the id and class attributes of the <li> element
            li.id = 'lesson-' + lesson.id;
            li.className = 'lessonlink';
            // Set the text content of the <li> element to the current item's name
            li.textContent = lesson.title;
            // Append the <li> element to the <ul> element
            li.addEventListener("click", lessonCLicked);
            lessonsList.appendChild(li);
        });
        // Do something with the loaded JSON data
    }
}

// Example usage:
loadAndHandleJSON('./course.json', setUpLessons);

function lessonCLicked(event) {
    var lessonLinks = document.querySelectorAll(".lessonlink");
    
    lessonLinks.forEach(function(link) {
        link.classList.remove("lessonlinkActive");
    });
    
    event.target.classList.add("lessonlinkActive");

    // Extract the ID of the clicked <li> element
    var id = event.target.id.replace("lesson-", "");
    SELECTEDLESSON = id;
    var lesson = LESSONS[id];

    console.log(lesson);

    // Display the lesson details
    var lessonDetails = document.getElementById("lessonDetails");
    if (lesson) {
        lessonDetails.innerHTML = "<h3>" + lesson.title + "</h3>";
        lessonDetails.innerHTML += "<p>" + lesson.overview + "</p>";
        lessonDetails.innerHTML += "<p><a class='button roboto-bold' target='_blank' href='" + lesson.link + "'> Read more </a></p>";
    } else {
        lessonDetails.innerHTML = "<p>Το μάθημα δεν βρέθηκε.</p>";
    }
}