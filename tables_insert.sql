INSERT INTO users (firstname, lastname, pseudo, email, password, role)
VALUES ('Lya', 'De Souza', 'admin', 'admin@example.com', '$2y$10$4xqwy6vRZsJvU4LflpWPUen3RK1NzjEMvbfhrPC.jEqsytMYB2qFu
', 2);

-- mot de passe : LyaAdmin123! --

INSERT INTO users (firstname, lastname, pseudo, email, password, role)
VALUES ('Studi', 'Studi', 'studitest12', 'employee@example.com', '$2y$10$M6.SuR4wMOEZ1G6uDZl7CuYL5zb.leX82LxdRPoWAIpIDYftNPmkO
', 1);

-- mot de passe : Studi26320! --

INSERT INTO gallery (title, image, description, size) VALUES
('Red', 'https://cdn.pixabay.com/photo/2021/06/02/21/37/abstract-6305508_1280.jpg', 'Tableau abstrait rouge', '1024x768'),
('Lac rose', 'https://cdn.pixabay.com/photo/2021/08/18/19/20/painting-6556384_1280.jpg', 'Paysage du lac rose', '1024x768'),
('La Plage', 'https://cdn.pixabay.com/photo/2018/08/19/07/05/background-3616101_1280.jpg', 'Une plage ensoleillée', '1024x768'),
('Vent', 'https://cdn.pixabay.com/photo/2022/09/18/23/54/black-woman-7464238_1280.jpg', 'Portrait au vent', '1024x768'),
('Mer', 'https://cdn.pixabay.com/photo/2021/08/18/19/26/background-6556413_1280.jpg', 'Paysage marin', '1024x768'),
('Feu', 'https://media.istockphoto.com/id/485991870/fr/vectoriel/jus-dorange.jpg?s=612x612&w=0&k=20&c=Dxf6-v2chZJtKGALkJEd9gD1YBY5Es25iv_yk5Udvzs=', 'Orange vibrant', '1024x768');


INSERT INTO workshops (name, level, date, max_places, description, duration) VALUES
('Atelier découverte', 'Tous niveaux', '2025-09-14 14:00:00', 10, 'Venez découvrir la peinture à l’acrylique lors d’un atelier convivial.', '3h'),
('Atelier créatif enfants', 'Débutant', '2025-09-28 10:00:00', 10, 'Atelier spécial enfants pour apprendre à créer des tableaux colorés.', '3h'),
('Atelier avancé', 'Intermédiaire', '2025-10-12 14:00:00', 10, 'Perfectionnez vos techniques de peinture et explorez de nouvelles approches.', '3h'),
('Atelier portrait', 'Tous niveaux', '2025-10-26 14:00:00', 10, 'Apprenez à dessiner et peindre des portraits réalistes.', '3h'),
('Atelier aquarelle', 'Débutant', '2025-11-09 10:00:00', 10, 'Introduction à l’aquarelle et techniques de mélange des couleurs.', '3h'),
('Atelier abstrait', 'Intermédiaire', '2025-11-23 14:00:00', 10, 'Exprimez votre créativité avec des formes et couleurs abstraites.', '3h'),
('Atelier nature', 'Tous niveaux', '2025-12-07 14:00:00', 10, 'Peinture de paysages et observation de la nature pour inspirer vos tableaux.', '3h');
