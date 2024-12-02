-- Insert Genres
INSERT INTO genres (id, name)
VALUES (28, 'Action'),
  (12, 'Adventure'),
  (16, 'Animation'),
  (35, 'Comedy'),
  (80, 'Crime'),
  (99, 'Documentary'),
  (18, 'Drama'),
  (10751, 'Family'),
  (14, 'Fantasy'),
  (36, 'History'),
  (27, 'Horror'),
  (10402, 'Music'),
  (9648, 'Mystery'),
  (10749, 'Romance'),
  (878, 'Science Fiction'),
  (10770, 'TV Movie'),
  (53, 'Thriller'),
  (10752, 'War'),
  (37, 'Western');
-- Insert Movies and Link with Genres
INSERT INTO movies (
    tmdb_id,
    title,
    overview,
    release_date,
    poster_path
  )
VALUES (
    912649,
    'Venom: The Last Dance',
    'Eddie and Venom are on the run...',
    '2024-10-22',
    '/aosm8NMQ3UyoBVpSxyimorCQykC.jpg'
  ),
  (
    1034541,
    'Terrifier 3',
    'Five years after surviving Art the Clown...',
    '2024-10-09',
    '/63xYQj1BwRFielxsBDXvHIJyXVm.jpg'
  ),
  (
    1184918,
    'The Wild Robot',
    'After a shipwreck, an intelligent robot called Roz...',
    '2024-09-12',
    '/wTnV3PCVW5O92JMrFvvrRcV39RU.jpg'
  ),
  (
    933260,
    'The Substance',
    'A fading celebrity decides to use a black market drug...',
    '2024-09-07',
    '/lqoMzCcZYEFK729d6qzt349fB4o.jpg'
  ),
  (
    698687,
    'Transformers One',
    'The untold origin story of Optimus Prime and Megatron...',
    '2024-09-11',
    '/iHPIBzrjJHbXeY9y7VVbEVNt7LW.jpg'
  ),
  (
    533535,
    'Deadpool & Wolverine',
    'A listless Wade Wilson toils away in civilian life...',
    '2024-07-24',
    '/8cdWjvZQUExUUTzyp4t6EDMubfO.jpg'
  ),
  (
    889737,
    'Joker: Folie à Deux',
    'While struggling with his dual identity, Arthur Fleck...',
    '2024-10-01',
    '/if8QiqCI7WAGImKcJCfzp6VTyKA.jpg'
  ),
  (
    1118031,
    'Apocalypse Z: The Beginning of the End',
    'When a kind of rabies that transforms people into aggressive creatures...',
    '2024-10-04',
    '/wIGJnIFQlESkC2rLpfA8EDHqk4g.jpg'
  ),
  (
    1084736,
    'The Count of Monte-Cristo',
    'Edmond Dantes becomes the target of a sinister plot...',
    '2024-06-28',
    '/zw4kV7npGtaqvUxvJE9IdqdFsNc.jpg'
  ),
  (
    1051896,
    'Arcadian',
    'In the near future, on a decimated Earth, Paul and his twin sons face terror...',
    '2024-04-12',
    '/spWV1eRzlDxvai8LbxwAWR0Vst4.jpg'
  ),
  (
    335983,
    'Venom',
    'Investigative journalist Eddie Brock attempts a comeback...',
    '2018-09-28',
    '/2uNW4WbgBXL25BAbXGLnLqX71Sw.jpg'
  ),
  (
    945961,
    'Alien: Romulus',
    'While scavenging the deep ends of a derelict space station...',
    '2024-08-13',
    '/b33nnKl1GSFbao4l3fZDDqsMx0F.jpg'
  ),
  (
    845781,
    'Red One',
    'After Santa Claus (codename: Red One) is kidnapped...',
    '2024-10-31',
    '/cdqLnri3NEGcmfnqwk2TSIYtddg.jpg'
  ),
  (
    580489,
    'Venom: Let There Be Carnage',
    'After finding a host body in investigative reporter Eddie Brock...',
    '2021-09-30',
    '/1MJNcPZy46hIy2CmSqOeru0yr5C.jpg'
  ),
  (
    1196470,
    'Survive',
    'A couple celebrates their son’s birthday in the middle of the ocean...',
    '2024-06-19',
    '/7fR3KxswtY8OHHZuOUB9td58CRX.jpg'
  ),
  (
    179387,
    'Heavenly Touch',
    'Jonard is having trouble making ends meet...',
    '2009-05-12',
    '/ory8WuAqznTE7lfopTSymHpop2t.jpg'
  ),
  (
    1022789,
    'Inside Out 2',
    'Teenager Riley’s mind headquarters is undergoing a sudden demolition...',
    '2024-06-11',
    '/vpnVM9B6NMmQpWeZvzLvDESb2QY.jpg'
  ),
  (
    976734,
    'Canary Black',
    'Top level CIA agent Avery Graves is blackmailed by terrorists...',
    '2024-10-10',
    '/hhiR6uUbTYYvKoACkdAIQPS5c6f.jpg'
  ),
  (
    519182,
    'Despicable Me 4',
    'Gru and Lucy and their girls—Margo, Edith and Agnes...',
    '2024-06-20',
    '/wWba3TaojhK7NdycRhoQpsG0FaH.jpg'
  ),
  (
    827931,
    'Time Cut',
    'A teen in 2024 accidentally time-travels to 2003...',
    '2024-10-29',
    '/gzMFMmpJHOmOFKsAhSDac62Dyg2.jpg'
  );
INSERT INTO movie_genres (movie_id, genre_id)
VALUES (912649, 878),
  (912649, 28),
  (912649, 12),
  (1034541, 27),
  (1034541, 53),
  (1034541, 9648),
  (1184918, 16),
  (1184918, 878),
  (1184918, 10751),
  (933260, 18),
  (933260, 27),
  (933260, 878),
  (698687, 16),
  (698687, 878),
  (698687, 12),
  (698687, 10751),
  (533535, 28),
  (533535, 35),
  (533535, 878),
  (889737, 18),
  (889737, 80),
  (889737, 53),
  (1118031, 18),
  (1118031, 28),
  (1118031, 27),
  (1084736, 12),
  (1084736, 36),
  (1084736, 28),
  (1084736, 18),
  (1084736, 10749),
  (1084736, 53),
  (1051896, 28),
  (1051896, 27),
  (1051896, 53),
  (1051896, 878),
  (335983, 878),
  (335983, 28),
  (945961, 878),
  (945961, 27),
  (845781, 35),
  (845781, 28),
  (845781, 14),
  (580489, 878),
  (580489, 28),
  (580489, 12),
  (1196470, 53),
  (1196470, 12),
  (1196470, 28),
  (1196470, 878),
  (179387, 18),
  (179387, 10749),
  (1022789, 16),
  (1022789, 10751),
  (1022789, 12),
  (1022789, 35),
  (1022789, 18),
  (976734, 28),
  (976734, 53),
  (976734, 80),
  (519182, 16),
  (519182, 10751),
  (519182, 35),
  (519182, 28),
  (827931, 27),
  (827931, 878),
  (827931, 53);