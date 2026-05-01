<?php 
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Subjects Explorer | EduTrack Pro</title>

    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .card {
            cursor: pointer;
            transition: 0.3s;
            border-radius: 15px;
        }
        .card:hover {
            transform: scale(1.05);
        }

        /* 💀 CLASS TEXT FIX */
        .class-card h4 {
            color: black !important;
        }

        .class-card {
    color: black !important;
}


    </style>
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-5">

    <h2 class="text-center mb-4">📚 Subjects Explorer</h2>

    <!-- 💀 CLASS AREA -->
    <div id="classes" class="row text-center">

        <div class="col-md-6">
            <div class="card p-4 class-card" onclick="showSections('11')">
                <h4>Class 11</h4>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-4 class-card" onclick="showSections('12')">
                <h4>Class 12</h4>
            </div>
        </div>

    </div>

    <!-- SECTION -->
    <div id="sections" class="mt-5 text-center"></div>

    <!-- SUBJECT -->
    <div id="subjects" class="mt-5 text-center"></div>

</div>

<script>

let currentClass = "";

// 💀 STEP 1 → CLASS CLICK
function showSections(cls){

    currentClass = cls;

    // hide classes
    document.getElementById("classes").style.display = "none";

    document.getElementById("sections").innerHTML = `
        <button class="btn btn-secondary mb-3" onclick="goBackToClasses()">⬅ Back</button>

        <h4>Class ${cls} Sections</h4>

        <div class="row mt-3">
            <div class="col-md-6">
                <div class="card p-3" onclick="showSubjects('${cls}','PCM')">
                    PCM
                </div>
            </div>

            <div class="col-md-6">
                <div class="card p-3" onclick="showSubjects('${cls}','PCB')">
                    PCB
                </div>
            </div>
        </div>
    `;

    document.getElementById("subjects").innerHTML = "";
}


// 💀 STEP 2 → SECTION CLICK
function showSubjects(cls, section){

    let subjects = [];

    if(section == "PCM"){
        subjects = ["Physics","Chemistry","Mathematics"];
    } else {
        subjects = ["Physics","Chemistry","Biology"];
    }

    let html = `
        <button class="btn btn-secondary mb-3" onclick="goBackToSections()">⬅ Back</button>

        <h4>Class ${cls} - ${section}</h4>
        <div class="row mt-3">
    `;

    subjects.forEach(sub => {
        html += `
            <div class="col-md-4">
                <div class="card p-3">
                    ${sub}
                </div>
            </div>
        `;
    });

    html += `</div>`;

    document.getElementById("subjects").innerHTML = html;
}


// 💀 BACK → CLASSES
function goBackToClasses(){
    document.getElementById("classes").style.display = "flex";
    document.getElementById("sections").innerHTML = "";
    document.getElementById("subjects").innerHTML = "";
}

// 💀 BACK → SECTIONS
function goBackToSections(){
    showSections(currentClass);
}

</script>

<?php include "footer.php"; ?>

</body>
</html>