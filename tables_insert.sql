INSERT INTO users (firstname, lastname, pseudo, email, password, role)
VALUES ('Lya', 'De Souza', 'admin', 'admin@example.com', '$2y$10$eCq2y9Hb1J7JtI6k4N7bLe8Q5Dk/1eN9JH/2Gz4u5a6tYl0A9Rz5G', 2);

-- mot de passe : LyaAdmin123! --

INSERT INTO users (firstname, lastname, pseudo, email, password, role)
VALUES ('Studi', 'Studi', 'studitest12', 'employee@example.com', '$2y$10$SzPPGn/ddHu3njbHqUMbAuwhHXogCROR8QesDcHCL19dXYMyO8.Nm', 1);

-- mot de passe : Studi26320! --

INSERT INTO gallery (title, image, description, size) VALUES
('Red', 'https://cdn.pixabay.com/photo/2021/06/02/21/37/abstract-6305508_1280.jpg', 'Tableau abstrait rouge', '1024x768'),
('Lac rose', 'https://cdn.pixabay.com/photo/2021/08/18/19/20/painting-6556384_1280.jpg', 'Paysage du lac rose', '1024x768'),
('La Plage', 'https://cdn.pixabay.com/photo/2018/08/19/07/05/background-3616101_1280.jpg', 'Une plage ensoleillée', '1024x768'),
('Vent', 'https://cdn.pixabay.com/photo/2022/09/18/23/54/black-woman-7464238_1280.jpg', 'Portrait au vent', '1024x768'),
('Mer', 'https://cdn.pixabay.com/photo/2021/08/18/19/26/background-6556413_1280.jpg', 'Paysage marin', '1024x768'),
('Feu', 'https://media.istockphoto.com/id/485991870/fr/vectoriel/jus-dorange.jpg?s=612x612&w=0&k=20&c=Dxf6-v2chZJtKGALkJEd9gD1YBY5Es25iv_yk5Udvzs=', 'Orange vibrant', '1024x768');
