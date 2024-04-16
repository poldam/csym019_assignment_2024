var LESSONS = [];
var SELECTEDLESSON = 0;
var SELECTEDCURR = 'euro';
var USDRATE = 1.27;
var EURORATE = 1.17;
var RELOADSECONDS = 100;

function loadAndHandleJSON(jsonFile, callback) {
    fetch(jsonFile)
        .then(response => response.json())
        .then(data => callback(null, data))
        .catch(error => callback(error, null));

    setTimeout(function () {
        loadAndHandleJSON('./course.json', setUpLessons);
    }, RELOADSECONDS * 1000);
}

function setUpLessons(error, lessons) {
    if (error) {
        console.error('Error loading JSON file:', error);
    } else {
        console.log('JSON file loaded successfully:', lessons);
        // Get the reference to the <ul> element
        var lessonsList = document.getElementById("lessonsList");
        lessonsList.innerHTML = '';

        lessons.forEach(function (lesson) {
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
            li.addEventListener("click", lessonClicked);
            lessonsList.appendChild(li);
        });

        if(SELECTEDLESSON)
            document.getElementById("lesson-" + SELECTEDLESSON).click();
    }
}

function lessonClicked(event) {
    var lessonLinks = document.querySelectorAll(".lessonlink");
    lessonLinks.forEach(function (link) {
        link.classList.remove("lessonlinkActive");
    });

    event.target.classList.add("lessonlinkActive");

    // Extract the ID of the clicked <li> element
    var id = event.target.id.replace("lesson-", "");
    SELECTEDLESSON = id;
    var lesson = LESSONS[id];

    console.log(lesson);

    var currency = "EUR";
    var rate = EURORATE;
    if (SELECTEDCURR == "pound") {
        currency = "GBP";
        rate = 1;
    }
    else if (SELECTEDCURR == "dollar") {
        currency = "USD";
        rate = USDRATE;
    }

    var formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: currency, // Change 'USD' to the appropriate currency code
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });

    var html = "";
    var extras = "";
    var header = "";
    var credits = "";

    // Display the lesson details
    var lessonDetails = document.getElementById("lessonDetails");
    var lessonKeys = document.getElementById("lessonKeys");

    lessonDetails.innerHTML = '';
    lessonKeys.innerHTML = '';

    if (lesson) {
        if (lesson.title)
            header += "<h2>" + lesson.title + "</h2>";

        if(lesson.image) {
            
            header += "<div class='leftHeader'><div class='square-container'> <img src='" + lesson.image + "' alt='Your Image'> </div>";
            if (lesson.link)
                header += "<p><a class='button roboto-bold' target='_blank' href='" + lesson.link + "'> Read more </a></p>";
            header += "</div>";

            header += "<div class='rightHeader'>";
            if (lesson.overview)
                header += "<p>" + lesson.overview + "</p>";
            header += "</div>";
        } else {
            
            if (lesson.overview)
                header += "<p>" + lesson.overview + "</p>";
            if (lesson.link)
                header += "<p><a class='button roboto-bold' target='_blank' href='" + lesson.link + "'> Read more </a></p>";
        }

        lessonDetails.innerHTML += header;

        if (lesson.COURSECONTENT) {
            lessonDetails.innerHTML += "<hr>";
            lessonDetails.innerHTML += "<h3>COURSE CONTENT</h3>";

            if (lesson.COURSECONTENT.CourseDetails) {
                html = "";
                if (lesson.COURSECONTENT.CourseDetails.html)
                    html = lesson.COURSECONTENT.CourseDetails.html

                if(lesson.COURSECONTENT.CourseDetails.stages) {
                    lesson.COURSECONTENT.CourseDetails.stages.forEach(function (item) {
                        html += "<button class='accordion2'>" + item.name + "</button>";

                        if(item.modules) {

                            item.html = "";
                                                        
                            item.modules.forEach(function (item2) {
                                credits = ""
                                if(item2.credits) {
                                    credits = " (" + item2.credits + " Credits)";
                                }

                                item.html += "<button class='accordion3'>" + item2.name + credits + " </button>";
                                item.html += "<div class='panel3'><br><div class='gray'><small><span class='roboto-bold'>Module code:</span> " + item2.code + " <span class='roboto-bold'>Status:</span> " + item2.status + " </small></div> <br>" + item2.description + "<br><br></div>";
                            });
                        }

                        html += "<div class='panel2'><br>" + item.html + "<br></div>";
                    });
                }

                lessonDetails.innerHTML += "<button class='accordion'>Course Details</button>";
                lessonDetails.innerHTML += "<div class='panel'><br>" + html + "<br></div>";
            }

            if (lesson.COURSECONTENT.EntryRequirements) {
                html = "";
                if (lesson.COURSECONTENT.EntryRequirements.html)
                    html = lesson.COURSECONTENT.EntryRequirements.html

                lessonDetails.innerHTML += "<button class='accordion'>Entry Requirements</button>";
                lessonDetails.innerHTML += "<div class='panel'><br>" + html + "<br></div>";
            }

            if (lesson.COURSECONTENT.FeesandFunding) {
                html = "";
                
                if(lesson.COURSECONTENT.FeesandFunding.title)
                    html +="<h3>" + lesson.COURSECONTENT.FeesandFunding.title + "</h3>";

                if(lesson.COURSECONTENT.FeesandFunding.text)
                    html +="<p>" + lesson.COURSECONTENT.FeesandFunding.text + "</p>";

                lesson.COURSECONTENT.FeesandFunding.Fees.forEach(function (item) {
                    extras = "";
                    if(item.extra)
                        extras = item.extra;
                    html += "<div><span class='roboto-medium'>" + item.type + "</span>: " + formatter.format(Math.round(rate*item.value)) + " " + extras + "</div>";
                });

                if(lesson.COURSECONTENT.FeesandFunding.AdditionalCosts) {
                    html +="<h3>Additional Costs</h3>";
                    html +="<p>" + lesson.COURSECONTENT.FeesandFunding.AdditionalCosts + "</p>";
                }

                lessonDetails.innerHTML += "<button class='accordion'>Fees and Funding</button>";
                lessonDetails.innerHTML += "<div class='panel'><br>" + html + "<br></div>";
            }

            if (lesson.COURSECONTENT.StudentPerks) {
                html = "";
                if (lesson.COURSECONTENT.StudentPerks.html)
                    html = lesson.COURSECONTENT.StudentPerks.html
                lessonDetails.innerHTML += "<button class='accordion'>Student Perks</button>";
                lessonDetails.innerHTML += "<div class='panel'><br>" + html + "<br></div>";
            }

            if (lesson.COURSECONTENT.IntegratedFoundationYear) {
                html = "";
                if (lesson.COURSECONTENT.IntegratedFoundationYear.html)
                    html = lesson.COURSECONTENT.IntegratedFoundationYear.html
                lessonDetails.innerHTML += "<button class='accordion'>Integrated Foundation Year (IFY)</button>";
                lessonDetails.innerHTML += "<div class='panel'><br>" + html + "<br></div>";
            }

            if (lesson.COURSECONTENT.FAQs) {
                html = "";
                if (lesson.COURSECONTENT.FAQs.html)
                    html = lesson.COURSECONTENT.FAQs.html;

                if(lesson.COURSECONTENT.FAQs.questions) {
                    lesson.COURSECONTENT.FAQs.questions.forEach(function (item) {
                        html += "<button class='accordion2'>" + item.q + "</button>";
                        html += "<div class='panel2'><br>" + item.a + "<br><br></div>";
                    });
                }
                
                lessonDetails.innerHTML += "<button class='accordion'>FAQs</button>";
                lessonDetails.innerHTML += "<div class='panel'><br>" + html + "<br></div>";
            }
        }

        if (lesson.KEYFACTS) {
            lessonKeys.innerHTML += "<h2>KEY FACTS</h2>";
            if (lesson.KEYFACTS.UCASCode) {
                lessonKeys.innerHTML += "<div class='roboto-bold'>UCAS Code</div>";
                lesson.KEYFACTS.UCASCode.forEach(function (item) {
                    lessonKeys.innerHTML += "<div><span class='roboto-medium'>" + item.type + "</span>: " + item.value + "</div>";
                });

                lessonKeys.innerHTML += "<hr>";
            }

            if (lesson.KEYFACTS.Level) {
                lessonKeys.innerHTML += "<div><span class='roboto-bold'>Level</span>: " + lesson.KEYFACTS.Level + "</div>";
                lessonKeys.innerHTML += "<hr>";
            }

            if (lesson.KEYFACTS.Duration) {
                lessonKeys.innerHTML += "<div class='roboto-bold'>Duration</div>";
                lesson.KEYFACTS.Duration.forEach(function (item) {
                    lessonKeys.innerHTML += "<div><span class='roboto-medium'>" + item.type + "</span>: " + item.value + "</div>";
                });

                lessonKeys.innerHTML += "<hr>";
            }

            if (lesson.KEYFACTS.Starting) {
                lessonKeys.innerHTML += "<div><span class='roboto-bold'>Starting</span>: " + lesson.KEYFACTS.Starting + "</div>";
                lessonKeys.innerHTML += "<hr>";
            }

            if (lesson.KEYFACTS.EntryRequirements) {
                lessonKeys.innerHTML += "<div class='roboto-bold'>Entry Requirements:</div><div>" + lesson.KEYFACTS.EntryRequirements + "</div>";
                lessonKeys.innerHTML += "<hr>";
            }

            if (lesson.KEYFACTS.Fees) {

                if (lesson.KEYFACTS.Fees.UK) {
                    lessonKeys.innerHTML += "<div class='roboto-bold'>Fees UK</div>";
                    
                    lesson.KEYFACTS.Fees.UK.forEach(function (item) {
                        extras = "";
                        if(item.extra)
                            extras = item.extra;
                        lessonKeys.innerHTML += "<div><span class='roboto-medium'>" + item.type + "</span>: " + formatter.format(Math.round(rate*item.value)) + " " + extras + "</div>";
                    });
                }

                lessonKeys.innerHTML += "<br>";

                if (lesson.KEYFACTS.Fees.International) {
                    lessonKeys.innerHTML += "<div class='roboto-bold'>Fees International</div>";
                    
                    lesson.KEYFACTS.Fees.International.forEach(function (item) {
                        extras = "";
                        if(item.extra)
                            extras = item.extra;
                        lessonKeys.innerHTML += "<div><span class='roboto-medium'>" + item.type + "</span>: " + formatter.format(Math.round(rate*item.value)) + " " + extras + "</div>";
                    });
                }

                lessonKeys.innerHTML += "<hr>";
            }

            if (lesson.KEYFACTS.Location) {
                lessonKeys.innerHTML += "<div><span class='roboto-bold'>Location</span>: " + lesson.KEYFACTS.Location + "</div>";
            }
        }

    } else {
        lessonDetails.innerHTML = "<p>Το μάθημα δεν βρέθηκε.</p>";
    }

    var acc = document.querySelectorAll(".accordion");

    for (var i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function () {
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.maxHeight) {
                panel.style.maxHeight = null;
            } else {
                panel.style.maxHeight = "10000px";
            }
        });
    }

    var acc2 = document.querySelectorAll(".accordion2");

    for (var j = 0; j < acc2.length; j++) {
        acc2[j].addEventListener("click", function () {
            this.classList.toggle("active");
            var panel2 = this.nextElementSibling;
            if (panel2.style.maxHeight) {
                panel2.style.maxHeight = null;
            } else {
                panel2.style.maxHeight = "10000px";
            }
        });
    }

    var acc3 = document.querySelectorAll(".accordion3");

    for (var k = 0; k < acc3.length; k++) {
        acc3[k].addEventListener("click", function () {
            this.classList.toggle("active");
            var panel3 = this.nextElementSibling;
            if (panel3.style.maxHeight) {
                panel3.style.maxHeight = null;
            } else {
                panel3.style.maxHeight = "10000px";
            }
        });
    }
}

function currencyChanged(event) {
    SELECTEDCURR = document.getElementById("currencySelector").value;
    // console.log(SELECTEDCURR);
    // console.log(SELECTEDLESSON);
    if(SELECTEDLESSON)
        document.getElementById("lesson-" + SELECTEDLESSON).click();
}

function getRates() {
    //https://openexchangerates.org/account/usage
    const apiKey = '11fdd6d5f1274db596c9fb847f5497c2';
    const apiUrl = `https://open.er-api.com/v6/latest?base=GBP&symbols=USD,EUR&apikey=${apiKey}`;

    // Fetch exchange rates from the API
    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            const exchangeRatesDiv = document.getElementById('exchangeRates');
            if (data.result == 'success') {
                USDRATE = data.rates.USD;
                EURORATE = data.rates.EUR;
                console.log(data.rates);
            } else {
                console.log("Failed to retrieve exchange rates.", data);
                USDRATE = 1.27;
                EURORATE = 1.17;
            }
        })
        .catch(error => {
            console.error('Error fetching exchange rates:', error);
            USDRATE = 1.27;
            EURORATE = 1.17;
        });
}

var currencySelector = document.getElementById("currencySelector");
currencySelector.addEventListener("change", currencyChanged);

// Uncomment to go live!
// getRates();

loadAndHandleJSON('./course.json', setUpLessons);



