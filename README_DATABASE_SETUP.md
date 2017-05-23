# blogs
## Green River College IT328 Full Stack Web Development
### Assignment: Blogs

This readme displays the MySQL code necessary to initiate the required database setup for the "blogs" assignment.

**Create bloggers table**
```SQL
CREATE TABLE bloggers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  userName VARCHAR(30) NOT NULL UNIQUE,
  email VARCHAR(255) NOT NULL UNIQUE,
  image VARCHAR(35) DEFAULT 'anon.png',
  bio TEXT,
  password VARCHAR(255) NOT NULL
) ENGINE=INNODB;
```

**Create blogs table**
```SQL
CREATE TABLE blogs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  author INT NOT NULL,
  title VARCHAR(100) NOT NULL,
  content TEXT NOT NULL,
  wordCount INT NOT NULL,
  datePosted DATETIME NOT NULL,
  dateEdited TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (author) REFERENCES bloggers(id)
) ENGINE=INNODB;
```