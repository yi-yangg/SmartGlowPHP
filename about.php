<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content="About page for me (Chong Yi Yang)">
    <meta name="keywords" content="About me, timetable">
    <meta name="author" content="Chong Yi Yang">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Me | Chong Yi Yang</title>
    <!-- Icon beside title link -->
    <link rel="icon" href="images/smartglow.ico" type="image/x-icon">

    <!-- Link for CSS style sheet -->
    <link rel="stylesheet" href="styles/style.css">
    <script src="scripts/enhancements.js"></script>
</head>

<body>
    <!-- Include header and timer banner -->
    <?php
    include_once("header.inc");
    include_once("timer.inc");
    ?>

    <!-- Hero for about page -->
    <section id="about-hero-sec" class="about-hero">
        <div class="hero-content">
            <h1>About Me</h1>
            <p>Here you will find detailed information about me, including my personal details, background and timetable
            </p>
        </div>
    </section>

    <!-- Section displaying my details -->
    <section id="details-sec" class="my-details">
        <figure>
            <img src="images/my_picture.jpg" alt="Photo of Chong Yi Yang">
            <figcaption>Photo of Me</figcaption>

        </figure>


        <h2>My Details</h2>
        <!-- Definition list for details -->
        <dl>
            <dt>Name</dt>
            <dd>Chong Yi Yang</dd>

            <dt>Student Number</dt>
            <dd>105297292</dd>

            <dt>Tutor's Name</dt>
            <dd>Fatma Mohammed</dd>

            <dt>Course</dt>
            <dd>Master of Cybersecurity</dd>

            <dt>Email</dt>
            <dd><a class="inline-link" href="mailto:105297292@student.swin.edu.au">105297292@student.swin.edu.au</a>
            </dd>
        </dl>

    </section>

    <!-- Timetable section -->
    <section class="timetable">
        <h2>Weekly Timetable</h2>
        <table>
            <thead>
                <tr>
                    <th class="time-col"></th>
                    <th>Monday</th>
                    <th>Tuesday</th>
                    <th>Wednesday</th>
                    <th>Thursday</th>
                    <th>Friday</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th rowspan="2">7am</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <th rowspan="2">8am</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td rowspan="2">
                        <div class="event">
                            <p>6:30pm - 8:30pm</p>

                            <p><strong>COS60011</strong></p>
                            <p>Live Online Lecture 1 - 1</p>
                        </div>

                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <th rowspan="2">9am</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <th rowspan="2">10am</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td rowspan="2">
                        <div class="event">
                            <p>10:30am - 11:30am</p>
                            <p><strong>TNE60002</strong></p>
                            <p>Lecture 1 - 1</p>
                        </div>

                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <th rowspan="2">11am</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td rowspan="6">
                        <div class="event">
                            <p>11.30am - 2.30pm</p>
                            <p><strong>TNE60002</strong></p>
                            <p>Class 1 - 2</p>
                        </div>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <th rowspan="2">12pm</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <th rowspan="2">1pm</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <th rowspan="2">2pm</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td rowspan="4">
                        <div class="event">
                            <p>2:30pm - 4:30pm</p>
                            <p><strong>COS60004</strong></p>
                            <p>Live Online Lecture 1 - 1</p>
                        </div>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <th rowspan="2">3pm</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <th rowspan="2">4pm</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td rowspan="4">
                        <div class="event">
                            <p>4:30pm - 6:30pm</p>
                            <p><strong>CYB80002</strong></p>
                            <p>Tutorial 1 - 2</p>
                        </div>
                    </td>
                </tr>

                <tr>
                    <th rowspan="2">5pm</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <th rowspan="2">6pm</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td rowspan="4">
                        <div class="event">
                            <p>6:30pm - 8:30pm</p>
                            <p><strong>COS60004</strong></p>
                            <p>Class 1 - 1</p>
                        </div>
                    </td>
                    <td rowspan="4">
                        <div class="event">
                            <p>6:30pm - 8:30pm</p>
                            <p><strong>COS60011</strong></p>
                            <p>Workshop 1 - 5</p>
                        </div>
                    </td>
                    <td rowspan="4">
                        <div class="event">
                            <p>6:30pm - 8:30pm</p>
                            <p><strong>CYB80002</strong></p>
                            <p>Live Online Lecture 1 - 1</p>
                        </div>
                    </td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <th rowspan="2">7pm</th>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <th rowspan="2">8pm</th>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

    </section>

    <!-- Include footer using php -->
    <?php
    include_once("footer.inc");
    ?>
</body>

</html>