<?php
// Database connection
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

// Fetch certificates from database
$certificates_sql = "SELECT * FROM certificates ORDER BY uploaded_at DESC";
$certificates_result = $conn->query($certificates_sql);

// Fetch gallery items from database
$gallery_sql = "SELECT * FROM gallery ORDER BY uploaded_at DESC";
$gallery_result = $conn->query($gallery_sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Me - Jagabandhu Prusty</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
      color: #fff;
      overflow-x: hidden;
    }

    .header {
      text-align: center;
      padding: 50px 20px;
    }

    .header h1 {
      font-size: 2.5rem;
      text-shadow: 0 0 15px cyan;
      animation: glow 2s infinite alternate;
    }

    @keyframes glow {
      from {
        text-shadow: 0 0 10px #00e6e6, 0 0 20px #00e6e6;
      }

      to {
        text-shadow: 0 0 20px #ff00ff, 0 0 40px #ff00ff;
      }
    }

    .container {
      max-width: 1000px;
      margin: auto;
      padding: 40px 20px;
      display: grid;
      grid-template-columns: 1fr 2fr;
      gap: 30px;
      align-items: center;
    }

    .profile-pic img {
      width: 100%;
      border-radius: 50%;
      border: 5px solid #00e6e6;
      box-shadow: 0 0 25px rgba(0, 230, 230, 0.7);
      transition: transform 0.4s;
    }

    .profile-pic img:hover {
      transform: scale(1.05);
    }

    .about-text h2 {
      font-size: 2rem;
      margin-bottom: 15px;
      color: #00e6e6;
    }

    .about-text p {
      font-size: 1.1rem;
      line-height: 1.6;
      margin-bottom: 20px;
    }

    .skills {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }

    .skill {
      background: rgba(255, 255, 255, 0.1);
      padding: 8px 16px;
      border-radius: 20px;
      font-size: 0.9rem;
      transition: 0.3s;
      border: 1px solid #00e6e6;
    }

    .skill:hover {
      background: #00e6e6;
      color: #000;
      transform: scale(1.1);
    }

    /* Sections */
    section {
      max-width: 1000px;
      margin: 50px auto;
      padding: 20px;
    }

    section h2 {
      font-size: 2rem;
      text-align: center;
      margin-bottom: 25px;
      color: #00e6e6;
      text-shadow: 0 0 10px #00e6e6;
    }

    .certificates,
    .photos {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }

    .card {
      background: rgba(255, 255, 255, 0.08);
      border-radius: 12px;
      padding: 15px;
      text-align: center;
      transition: 0.3s;
      box-shadow: 0 0 15px rgba(0, 230, 230, 0.3);
      cursor: pointer;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      border-radius: 10px;
      margin-bottom: 10px;
      transition: transform 0.3s;
    }

    .card img:hover {
      transform: scale(1.05);
    }

    .card p {
      font-size: 1rem;
      color: #ddd;
    }

    .empty-message {
      text-align: center;
      grid-column: 1 / -1;
      padding: 30px;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 10px;
    }

    /* Modal Styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.9);
      overflow: auto;
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      display: block;
      max-width: 90%;
      max-height: 80%;
      margin: auto;
      border-radius: 8px;
      box-shadow: 0 0 25px rgba(0, 230, 230, 0.5);
    }

    .modal-info {
      color: white;
      text-align: center;
      padding: 15px;
      max-width: 80%;
      margin: 0 auto;
    }

    .close {
      position: absolute;
      top: 20px;
      right: 30px;
      color: #f1f1f1;
      font-size: 40px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }

    .close:hover {
      color: #00e6e6;
      transform: scale(1.2);
    }

    .modal-nav {
      position: absolute;
      top: 50%;
      width: 100%;
      display: flex;
      justify-content: space-between;
      padding: 0 20px;
      transform: translateY(-50%);
    }

    .nav-btn {
      color: white;
      font-size: 40px;
      cursor: pointer;
      background: rgba(0, 0, 0, 0.5);
      border-radius: 50%;
      width: 50px;
      height: 50px;
      display: flex;
      justify-content: center;
      align-items: center;
      transition: 0.3s;
    }

    .nav-btn:hover {
      color: #00e6e6;
      background: rgba(0, 0, 0, 0.8);
      transform: scale(1.1);
    }

    /* Responsive */
    @media(max-width: 768px) {
      .container {
        grid-template-columns: 1fr;
        text-align: center;
      }

      .profile-pic img {
        max-width: 250px;
      }
      
      .modal-content {
        max-width: 95%;
        max-height: 60%;
      }
      
      .nav-btn {
        font-size: 30px;
        width: 40px;
        height: 40px;
      }
    }
  </style>
</head>

<body>

  <div class="header">
    <h1>About Me</h1>
  </div>

  <div class="container">
    <div class="profile-pic">
      <img src="devloper.png" alt="Jagabandhu Prusty">
    </div>
    <div class="about-text">
      <h2>Hi, I'm Jagabandhu Prusty 👋</h2>
      <p>
        I'm a passionate <strong>B.Tech CSE (AI & ML)</strong> student at <strong>VSSUT Burla</strong>, from
        <strong>Bhuban, Odisha</strong>.
        My ambition is to become a skilled <strong>AI & ML Engineer</strong> and reach new heights in technology with an
        inspiring career.
      </p>
      <p>
        I build modern web projects like <strong>Vaikunth (social media platform)</strong>,
        <strong>Smart Ticket Authentication (QR System)</strong>, and an AI-powered <strong>Bhagavad Gita
          chatbot</strong>.
        Apart from development, I'm always learning about <strong>cloud computing, backend systems, and data
          science</strong>.
      </p>
      <h3>Skills & Tools</h3>
      <div class="skills">
        <span class="skill">HTML</span>
        <span class="skill">CSS</span>
        <span class="skill">PHP</span>
        <span class="skill">MySQL</span>
        <span class="skill">Python</span>
        <span class="skill">C++</span>
        <span class="skill">C</span>
        <span class="skill">NumPy</span>
        <span class="skill">pandas</span>
        <span class="skill">scikit-learn</span>
        <span class="skill">AI & ML</span>
      </div>
    </div>
  </div>

  <!-- Certificates Section -->
  <section>
    <h2>Certificates</h2>
    <div class="certificates">
      <?php
      $certificates = [];
      if ($certificates_result && $certificates_result->num_rows > 0) {
        while ($row = $certificates_result->fetch_assoc()) {
          $certificates[] = $row;
          echo '<div class="card" onclick="openModal(\'certificates\', ' . (count($certificates) - 1) . ')">';
          echo '<img src="../' . $row['image_path'] . '" alt="' . $row['title'] . '">';
          echo '<p>' . $row['title'] . '</p>';
          if (!empty($row['issuing_org'])) {
            echo '<p><small>Issued by: ' . $row['issuing_org'] . '</small></p>';
          }
          echo '</div>';
        }
      } else {
        echo '<div class="empty-message">';
        echo '<p>No certificates uploaded yet. Check back soon!</p>';
        echo '</div>';
      }
      ?>
    </div>
  </section>

  <!-- Photos Section -->
  <section>
    <h2>Gallery</h2>
    <div class="photos">
      <?php
      $gallery = [];
      if ($gallery_result && $gallery_result->num_rows > 0) {
        while ($row = $gallery_result->fetch_assoc()) {
          $gallery[] = $row;
          echo '<div class="card" onclick="openModal(\'gallery\', ' . (count($gallery) - 1) . ')">';
          echo '<img src="../' . $row['image_path'] . '" alt="' . $row['title'] . '">';
          echo '<p>' . $row['title'] . '</p>';
          if (!empty($row['description'])) {
            echo '<p><small>' . $row['description'] . '</small></p>';
          }
          echo '</div>';
        }
      } else {
        echo '<div class="empty-message">';
        echo '<p>No gallery items uploaded yet. Check back soon!</p>';
        echo '</div>';
      }

      // Close connection
      $conn->close();
      ?>
    </div>
  </section>

  <!-- Modal -->
  <div id="imageModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <div class="modal-nav">
      <div class="nav-btn" onclick="navigateImage(-1)">&#10094;</div>
      <div class="nav-btn" onclick="navigateImage(1)">&#10095;</div>
    </div>
    <img class="modal-content" id="modalImage">
    <div class="modal-info" id="modalInfo"></div>
  </div>

  <script>
    // Store the data from PHP in JavaScript arrays
    const certificatesData = <?php echo json_encode($certificates); ?>;
    const galleryData = <?php echo json_encode($gallery); ?>;
    
    let currentCollection = '';
    let currentIndex = 0;
    
    // Scroll animations
    const elements = document.querySelectorAll('.about-text, .profile-pic, .skills .skill, .card');
    window.addEventListener('scroll', () => {
      elements.forEach(el => {
        const rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight - 100) {
          el.style.opacity = 1;
          el.style.transform = 'translateY(0)';
        }
      });
    });

    // Initial hidden state
    elements.forEach(el => {
      el.style.opacity = 0;
      el.style.transform = 'translateY(50px)';
      el.style.transition = 'all 0.6s ease-out';
    });

    // Modal functions
    function openModal(collection, index) {
      currentCollection = collection;
      currentIndex = index;
      
      const modal = document.getElementById('imageModal');
      const modalImage = document.getElementById('modalImage');
      const modalInfo = document.getElementById('modalInfo');
      
      const data = collection === 'certificates' ? certificatesData : galleryData;
      const item = data[index];
      
      modalImage.src = '../' + item.image_path;
      modalImage.alt = item.title;
      
      let infoHTML = `<h3>${item.title}</h3>`;
      if (collection === 'certificates' && item.issuing_org) {
        infoHTML += `<p>Issued by: ${item.issuing_org}</p>`;
      }
      if (collection === 'gallery' && item.description) {
        infoHTML += `<p>${item.description}</p>`;
      }
      
      modalInfo.innerHTML = infoHTML;
      modal.style.display = 'flex';
      document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
    }

    function closeModal() {
      document.getElementById('imageModal').style.display = 'none';
      document.body.style.overflow = 'auto'; // Re-enable scrolling
    }

    function navigateImage(direction) {
      const data = currentCollection === 'certificates' ? certificatesData : galleryData;
      currentIndex = (currentIndex + direction + data.length) % data.length;
      openModal(currentCollection, currentIndex);
    }

    // Close modal when clicking outside the image
    window.onclick = function(event) {
      const modal = document.getElementById('imageModal');
      if (event.target === modal) {
        closeModal();
      }
    };

    // Keyboard navigation
    document.addEventListener('keydown', function(event) {
      const modal = document.getElementById('imageModal');
      if (modal.style.display === 'flex') {
        if (event.key === 'Escape') {
          closeModal();
        } else if (event.key === 'ArrowLeft') {
          navigateImage(-1);
        } else if (event.key === 'ArrowRight') {
          navigateImage(1);
        }
      }
    });
  </script>
</body>

</html>