CREATE TABLE tasks (
  id serial PRIMARY KEY,
  title varchar(255) NOT NULL,
  description text NOT NULL,
  status boolean NOT NULL
);
