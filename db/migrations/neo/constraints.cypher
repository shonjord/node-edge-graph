CREATE CONSTRAINT ON (person:Person) ASSERT person.id IS UNIQUE;
CREATE CONSTRAINT ON (person:Person) ASSERT person.email IS UNIQUE;
CREATE CONSTRAINT ON (stream:Stream) ASSERT stream.name IS UNIQUE;
