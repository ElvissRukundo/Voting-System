CREATE DATABASE weDecideDB;

USE weDecideDB;

CREATE TABLE voters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    dob DATE NOT NULL,
    gender ENUM('male', 'female') NOT NULL,
    nin CHAR(14) NOT NULL UNIQUE CHECK (nin REGEXP '^C(M|F)[A-Za-z0-9]{12}$'),
    profile_pic VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    voter_id CHAR(6) NOT NULL UNIQUE
);

CREATE TABLE political_parties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    acronym VARCHAR(10) NOT NULL UNIQUE,
    logo VARCHAR(255) NOT NULL
);

INSERT INTO political_parties (fullname, acronym, logo) VALUES
('Alliance for National Transformation', 'ANT', 'assets/images/ant.png'),
('Democratic Party', 'DP', 'assets/images/dp.png'),
('Independent', 'IND', 'assets/images/ind.png'),
('Forum for Democratic Change', 'FDC', 'assets/images/fdc.png'),
('National Resistance Movement', 'NRM', 'assets/images/nrm.png'),
('National Unity Platform', 'NUP', 'assets/images/nup.png');

CREATE TABLE candidates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidates_name VARCHAR(100) NOT NULL,
    date_of_birth DATE NOT NULL,
    candidate_image VARCHAR(255) NOT NULL,
    political_party_id INT NOT NULL,
    political_party_name VARCHAR(100) NOT NULL,
    party_logo VARCHAR(255) NOT NULL,
    FOREIGN KEY (political_party_id) REFERENCES political_parties(id) ON DELETE CASCADE
);

INSERT INTO candidates (candidates_name, date_of_birth, candidate_image, political_party_id, political_party_name, party_logo) VALUES
('Fred Mwesigye', '1976-09-09', 'assets/images/fred.png', 3, 'Independent', 'assets/images/ind.png'),
('Henry Tumukunde', '1959-02-28', 'assets/images/tumukunde.png', 3, 'Independent', 'assets/images/ind.png'),
('John Katumba', '1996-02-22', 'assets/images/katumba.png', 3, 'Independent', 'assets/images/ind.png'),
('Joseph Kabuleta', '1972-03-01', 'assets/images/kabuleta.png', 3, 'Independent', 'assets/images/ind.png'),
('Mugisha Muntu', '1961-10-07', 'assets/images/muntu.png', 1, 'Alliance for National Transformation', 'assets/images/ant.png'),
('Nancy Kalembe', '1980-07-0', 'assets/images/nancy.png', 3, 'Independent', 'assets/images/ind.png'),
('Norbert Mao', '1967-03-12', 'assets/images/mao.png', 2, 'Democratic Party', 'assets/images/dp.png'),
('Patrick Oboi Amuriat', '1958-02-09', 'assets/images/amuriat.png', 4, 'Forum for Democratic Change', 'assets/images/fdc.png'),
('Robert Kyagulanyi', '1982-02-12', 'assets/images/kyagulanyi.png', 6, 'National Unity Platform', 'assets/images/nup.png'),
('Willy Mayambala', '1980-07-15', 'assets/images/mayambala.png', 3, 'Independent', 'assets/images/ind.png'),
('Yoweri Museveni', '1944-08-15', 'assets/images/museveni.png', 5, 'National Resistance Movement', 'assets/images/nrm.png');

CREATE TABLE votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    candidates_name VARCHAR(255) NOT NULL,
    party VARCHAR(255) NOT NULL,
    vote_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    token VARCHAR(255) NOT NULL,
    expiry DATETIME NOT NULL
);

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

INSERT INTO admins (username, password) VALUES
    ('admin', SHA2('admin123', 256);
)