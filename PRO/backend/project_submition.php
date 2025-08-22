<?php
// Database configuration
$localhost = "localhost";
$username = "root";
$password = "Jaga2457";
$database = "protfolio_project";

// Create connection
$conn = new mysqli($localhost, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Determine which form was submitted
$formType = isset($_POST['formType']) ? $_POST['formType'] : 'project';

if ($formType === 'project') {
    // Handle project submission
    $category = $conn->real_escape_string($_POST['projectCategory']);
    $project_name = $conn->real_escape_string($_POST['projectName']);
    $github_repo = $conn->real_escape_string($_POST['githubRepo']);
    $about_project = $conn->real_escape_string($_POST['aboutProject']);
    $languages_used = $conn->real_escape_string($_POST['languagesUsed']);

    // Handle optional fields
    $project_link = isset($_POST['projectLink']) ? $conn->real_escape_string($_POST['projectLink']) : null;

    // Handle project logo upload
    $project_logo = null;
    if (isset($_FILES['projectLogo']) && $_FILES['projectLogo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/project_logo/';
        $file_name = time() . '_' . basename($_FILES['projectLogo']['name']);
        $target_path = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['projectLogo']['tmp_name'], $target_path)) {
            $project_logo = str_replace('../', '', $target_path); // Remove ../ for database storage
        }

    }

    // Handle project document upload
    $project_document = null;
    if (isset($_FILES['projectDocument']) && $_FILES['projectDocument']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/document/';
        $file_name = time() . '_' . basename($_FILES['projectDocument']['name']);
        $target_path = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['projectDocument']['tmp_name'], $target_path)) {
            $project_document = str_replace('../', '', $target_path); // Remove ../ for database storage
        }

    }

    // Prepare SQL query
    $sql = "INSERT INTO projects (
        category, 
        project_name, 
        project_logo,
        github_repo, 
        project_document, 
        project_link, 
        about_project, 
        languages_used
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssss",
        $category,
        $project_name,
        $project_logo,
        $github_repo,
        $project_document,
        $project_link,
        $about_project,
        $languages_used
    );

} elseif ($formType === 'certificate') {
    // Handle certificate upload
    $title = $conn->real_escape_string($_POST['certificateTitle']);
    $issuing_org = isset($_POST['issuingOrg']) ? $conn->real_escape_string($_POST['issuingOrg']) : null;
    $issue_date = isset($_POST['issueDate']) ? $conn->real_escape_string($_POST['issueDate']) : null;
    $description = isset($_POST['certificateDescription']) ? $conn->real_escape_string($_POST['certificateDescription']) : null;

    // Handle certificate image upload
    $image_path = null;
    if (isset($_FILES['certificateImage']) && $_FILES['certificateImage']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/certificates/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = time() . '_' . basename($_FILES['certificateImage']['name']);
        $target_path = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['certificateImage']['tmp_name'], $target_path)) {
            $image_path = str_replace('../', '', $target_path); // Remove ../ for database storage
        }
    }

    // Prepare SQL query
    $sql = "INSERT INTO certificates (
        image_path, 
        title, 
        issuing_org, 
        issue_date, 
        description
    ) VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssss",
        $image_path,
        $title,
        $issuing_org,
        $issue_date,
        $description
    );

} elseif ($formType === 'gallery') {
    // Handle gallery upload
    $title = $conn->real_escape_string($_POST['imageTitle']);
    $description = isset($_POST['galleryDescription']) ? $conn->real_escape_string($_POST['galleryDescription']) : null;
    $category = isset($_POST['galleryCategory']) ? $conn->real_escape_string($_POST['galleryCategory']) : null;

    // Handle gallery image upload
    $image_path = null;
    if (isset($_FILES['galleryImage']) && $_FILES['galleryImage']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/gallery/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = time() . '_' . basename($_FILES['galleryImage']['name']);
        $target_path = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['galleryImage']['tmp_name'], $target_path)) {
            $image_path = str_replace('../', '', $target_path); // Remove ../ for database storage
        }
    }

    // Prepare SQL query
    $sql = "INSERT INTO gallery (
        image_path, 
        title, 
        description, 
        category
    ) VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssss",
        $image_path,
        $title,
        $description,
        $category
    );
}

// Execute the query
if ($stmt->execute()) {
    echo "Form submitted successfully!";
    header("Location: project.html");
} else {
    echo "Error: " . $stmt->error;
}

// Close connections
$stmt->close();
$conn->close();
?>