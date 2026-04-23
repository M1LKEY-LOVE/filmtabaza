CREATE TABLE hry (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    nazov VARCHAR(255) NOT NULL,
    zaner VARCHAR(100),
    vydavatel VARCHAR(100),
    rok_vydania INT,
    platforma VARCHAR(50),
    stav VARCHAR(50) DEFAULT 'voľné'
    hra_id INT,
);



DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    meno VARCHAR(50) NOT NULL,
    priezvisko VARCHAR(50) NOT NULL,
    heslo VARCHAR(255) NOT NULL, 
    telefon VARCHAR(20) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL
    user_id INT
);


CREATE TABLE vypozicky (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    hra_id INT,
    datum_vypozicky TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (hra_id) REFERENCES hry(id)
);


INSERT INTO hry (nazov, zaner, vydavatel, rok_vydania, platforma, stav) VALUES
('The Witcher 3: Wild Hunt', 'RPG', 'CD Projekt', 2015, 'PS5', 'voľné'),
('Grand Theft Auto V', 'Akčná adventúra', 'Rockstar Games', 2013, 'Xbox Series X', 'voľné'),
('Elden Ring', 'Akčné RPG', 'Bandai Namco', 2022, 'PC', 'voľné'),
('Minecraft', 'Sandbox', 'Mojang', 2011, 'Nintendo Switch', 'voľné'),
('Red Dead Redemption 2', 'Westernová akcia', 'Rockstar Games', 2018, 'PS5', 'voľné'),
('Counter-Strike 2', 'FPS', 'Valve', 2023, 'PC', 'voľné'),
('God of War Ragnarök', 'Akčná adventúra', 'Sony Interactive', 2022, 'PS5', 'voľné'),
('Cyberpunk 2077', 'RPG', 'CD Projekt', 2020, 'Xbox Series X', 'voľné'),
('Hades', 'Roguelike', 'Supergiant Games', 2020, 'Nintendo Switch', 'voľné'),
('The Last of Us Part II', 'Survival horor', 'Sony Interactive', 2020, 'PS5', 'voľné'),
('Baldur''s Gate 3', 'RPG', 'Larian Studios', 2023, 'PC', 'voľné'),
('Diablo IV', 'Akčné RPG', 'Blizzard', 2023, 'Xbox Series X', 'voľné'),
('Forza Horizon 5', 'Preteky', 'Xbox Game Studios', 2021, 'PC', 'voľné'),
('Stardew Valley', 'Simulátor farmárčenia', 'ConcernedApe', 2016, 'Nintendo Switch', 'voľné'),
('Dota 2', 'MOBA', 'Valve', 2013, 'PC', 'voľné'),
('Skyrim', 'RPG', 'Bethesda', 2011, 'Xbox Series X', 'voľné'),
('Apex Legends', 'Battle Royale', 'EA', 2019, 'PS5', 'voľné'),
('Valorant', 'Taktická FPS', 'Riot Games', 2020, 'PC', 'voľné'),
('Spider-Man 2', 'Akčná adventúra', 'Sony Interactive', 2023, 'PS5', 'voľné'),
('Horizon Forbidden West', 'Akčná adventúra', 'Sony Interactive', 2022, 'PC', 'voľné'),
('Assassin''s Creed Valhalla', 'Akčné RPG', 'Ubisoft', 2020, 'Xbox Series X', 'voľné'),
('Overwatch 2', 'FPS', 'Blizzard', 2022, 'Nintendo Switch', 'voľné'),
('Fortnite', 'Battle Royale', 'Epic Games', 2017, 'PS5', 'voľné'),
('Terraria', 'Sandbox', 'Re-Logic', 2011, 'PC', 'voľné'),
('Resident Evil Village', 'Horor', 'Capcom', 2021, 'Xbox Series X', 'voľné'),
('Doom Eternal', 'FPS', 'Bethesda', 2020, 'PS5', 'voľné'),
('Starfield', 'Sci-fi RPG', 'Bethesda', 2023, 'PC', 'voľné'),
('Final Fantasy VII Rebirth', 'RPG', 'Square Enix', 2024, 'PS5', 'voľné'),
('It Takes Two', 'Kooperatívna', 'EA', 2021, 'Xbox Series X', 'voľné'),
('League of Legends', 'MOBA', 'Riot Games', 2009, 'PC', 'voľné');


INSERT INTO users (meno, priezvisko, heslo, telefon, email) VALUES 
('Michal', 'Kováč', 'michal2024', '0905111222', 'michal.kovac@gmail.com'),
('Jana', 'Nagyová', 'janicka99', '0911333444', 'jana.nagyova@gmail.com'),
('Peter', 'Varga', 'petovarga1', '0902555666', 'peter.varga@gmail.com'),
('Lucia', 'Bieliková', 'lucy_pass', '0948777888', 'lucia.bielikova@gmail.com'),
('Marek', 'Tóth', 'marek_toth', '0903999000', 'marek.toth@gmail.com'),
('Zuzana', 'Molnárová', 'zuzi12345', '0915222333', 'zuzana.molnarova@gmail.com'),
('Tomáš', 'Horváth', 'tommy_h', '0907444555', 'tomas.horvath@gmail.com'),
('Simona', 'Králová', 'simi_secret', '0940666777', 'simona.kralova@gmail.com'),
('Andrej', 'Sloboda', 'andrej_sl', '0910888999', 'andrej.sloboda@gmail.com'),
('Monika', 'Ševčíková', 'monika_pass', '0908123456', 'monika.sevcikova@gmail.com');
