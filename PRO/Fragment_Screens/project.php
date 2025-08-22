<?php
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

// Fetch projects
$stmt = $conn->prepare("SELECT * FROM projects ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
$projects = [];
while ($row = $result->fetch_assoc()) {
    $projects[] = $row;
}
$stmt->close();
$conn->close();

// Separate into deployed and non-deployed
$deployed = [];
$non_deployed = [];
foreach ($projects as $p) {
    $link = isset($p['project_link']) ? trim($p['project_link']) : '';
    if ($link !== '') {
        $deployed[] = $p;
    } else {
        $non_deployed[] = $p;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>My Projects - Jaga</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
      color: #fff;
      font-family: 'Poppins', sans-serif;
    }
    .header {
      text-align: center;
      padding: 30px;
    }
    .header h1 {
      font-size: 2.5rem;
      text-shadow: 0 0 15px cyan;
      margin: 0;
    }
    .section {
      max-width: 1200px;
      margin: 30px auto;
      padding: 20px;
      background: rgba(255,255,255,0.02);
      border-radius: 12px;
      border: 1px solid rgba(0,230,230,0.08);
    }
    .section-title {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 18px;
    }
    .section-title h2 {
      font-size: 1.4rem;
      color: #00e6e6;
      margin: 0;
      text-shadow: 0 0 8px rgba(0,230,230,0.15);
    }
    .badge {
      background: rgba(0,230,230,0.12);
      color: #00e6e6;
      padding: 6px 10px;
      border-radius: 999px;
      font-weight: 600;
      font-size: 0.9rem;
      border: 1px solid rgba(0,230,230,0.25);
    }

    .projects-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 20px;
      padding-top: 6px;
    }
    .project-card {
      background: rgba(255, 255, 255, 0.06);
      border-radius: 15px;
      padding: 18px;
      transition: transform 0.3s, box-shadow 0.3s;
      border: 1px solid rgba(0, 230, 230, 0.45);
      box-shadow: 0 0 12px rgba(0,230,230,0.12);
    }
    .project-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 0 28px rgba(0,230,230,0.55);
    }
    .project-image {
      width: 100%;
      height: 180px;
      object-fit: cover;
      border-radius: 10px;
      margin-bottom: 12px;
      border: 1px solid rgba(255,255,255,0.1);
    }
    .project-card h3 {
      color: #00e6e6;
      margin: 0 0 8px 0;
      font-size: 1.1rem;
    }
    .project-card p {
      font-size: 0.95rem;
      margin: 6px 0;
      line-height: 1.45;
      color: #e6f7f7;
    }
    .project-links a {
      display: inline-block;
      margin: 6px 8px 0 0;
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 0.9rem;
      text-decoration: none;
      color: #000;
      background: #00e6e6;
      transition: 0.25s;
    }
    .project-links a:hover {
      background: #ff00ff;
      color: #fff;
    }
    .meta {
      margin-top:10px;
      font-size:0.85rem;
      color:#aaa;
    }

    /* Different style for non-deployed cards */
    .non-deployed .project-card {
      border: 1px solid rgba(255,255,255,0.06);
      box-shadow: 0 0 10px rgba(255,255,255,0.03);
      background: rgba(255,255,255,0.03);
    }
    .note {
      color:#ccc;
      font-size:0.95rem;
      margin-top:6px;
    }

    footer {
      text-align: center;
      padding: 20px;
      background: rgba(0,0,0,0.6);
      margin-top: 30px;
      font-size: 0.9rem;
    }

    @media (max-width:600px){
      .header h1 { font-size: 1.8rem; }
    }
  </style>
</head>
<body>
  <div class="header">
    <h1>My Projects</h1>
  </div>

  <div class="section">
  <div class="section-title">
    <h2>Deployed Projects (Live Demo)</h2>
    <div class="badge"><?php echo count($deployed); ?> deployed</div>
  </div>

  <?php if (!empty($deployed)): ?>
    <div class="projects-container">
      <?php foreach ($deployed as $project): ?>
        <div class="project-card">
          <?php if (!empty($project['project_logo'])): ?>
            <img src="../<?php echo htmlspecialchars($project['project_logo']); ?>" 
                 alt="<?php echo htmlspecialchars($project['project_name']); ?>" 
                 class="project-image">
          <?php else: ?>
            <img src="https://via.placeholder.com/320x180/2c5364/00e6e6?text=<?php echo urlencode($project['project_name']); ?>" 
                 alt="<?php echo htmlspecialchars($project['project_name']); ?>" 
                 class="project-image">
          <?php endif; ?>

          <h3><?php echo htmlspecialchars($project['project_name']); ?></h3>
          <p><strong>Category:</strong> <?php echo htmlspecialchars($project['category']); ?></p>
          <p><?php echo htmlspecialchars($project['about_project']); ?></p>
          <p><strong>Languages:</strong> <?php echo htmlspecialchars($project['languages_used']); ?></p>

          <div class="project-links">
            <?php if (!empty($project['github_repo'])): ?>
              <a href="<?php echo htmlspecialchars($project['github_repo']); ?>" target="_blank" rel="noopener noreferrer">GitHub</a>
            <?php endif; ?>
            <?php if (!empty($project['project_link'])): ?>
              <a href="<?php echo htmlspecialchars($project['project_link']); ?>" target="_blank" rel="noopener noreferrer">Live Demo</a>
            <?php endif; ?>
            <?php if (!empty($project['project_document'])): ?>
              <a href="../<?php echo htmlspecialchars($project['project_document']); ?>" target="_blank" rel="noopener noreferrer">Docs</a>
            <?php endif; ?>
          </div>

          <p class="meta">Created: <?php echo htmlspecialchars($project['created_at']); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p class="note">No deployed projects found.</p>
  <?php endif; ?>
</div>


  <div class="section non-deployed">
    <div class="section-title">
      <h2>Non-deployed Projects (No Live Demo)</h2>
      <div class="badge"><?php echo count($non_deployed); ?> not deployed</div>
    </div>

    <?php if (!empty($non_deployed)): ?>
      <div class="projects-container">
        <?php foreach ($non_deployed as $project): ?>
          <div class="project-card">
            <?php if (!empty($project['project_logo'])): ?>
              <img src="backend/<?php echo htmlspecialchars($project['project_logo']); ?>" alt="<?php echo htmlspecialchars($project['project_name']); ?>" class="project-image">
            <?php else: ?>
              <img src="https://via.placeholder.com/320x180/2c5364/00e6e6?text=<?php echo urlencode($project['project_name']); ?>" alt="<?php echo htmlspecialchars($project['project_name']); ?>" class="project-image">
            <?php endif; ?>
            <h3><?php echo htmlspecialchars($project['project_name']); ?></h3>
            <p><strong>Category:</strong> <?php echo htmlspecialchars($project['category']); ?></p>
            <p><?php echo htmlspecialchars($project['about_project']); ?></p>
            <p><strong>Languages:</strong> <?php echo htmlspecialchars($project['languages_used']); ?></p>
            <div class="project-links">
              <?php if (!empty($project['github_repo'])): ?>
                <a href="<?php echo htmlspecialchars($project['github_repo']); ?>" target="_blank" rel="noopener noreferrer">GitHub</a>
              <?php endif; ?>
              <?php if (!empty($project['project_document'])): ?>
                <a href="backend/<?php echo htmlspecialchars($project['project_document']); ?>" target="_blank" rel="noopener noreferrer">Docs</a>
              <?php endif; ?>
            </div>
            <p class="meta">Created: <?php echo htmlspecialchars($project['created_at']); ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="note">No non-deployed projects found.</p>
    <?php endif; ?>
  </div>
</body>
</html>