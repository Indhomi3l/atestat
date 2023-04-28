<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230427144411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE album_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE artist_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE genre_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE lyric_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE song_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE album (id BIGINT NOT NULL, album_type VARCHAR(255) NOT NULL, spotify_url VARCHAR(255) DEFAULT NULL, spotify_id VARCHAR(255) NOT NULL, images json NOT NULL, name VARCHAR(255) NOT NULL, release_date VARCHAR(255) NOT NULL, spotify_api_uri VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE artist (id BIGINT NOT NULL, spotify_url VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE artists_albums (artist_id BIGINT NOT NULL, album_id BIGINT NOT NULL, PRIMARY KEY(artist_id, album_id))');
        $this->addSql('CREATE INDEX IDX_144789E2B7970CF8 ON artists_albums (artist_id)');
        $this->addSql('CREATE INDEX IDX_144789E21137ABCF ON artists_albums (album_id)');
        $this->addSql('CREATE TABLE genre (id BIGINT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_835033F85E237E06 ON genre (name)');
        $this->addSql('CREATE TABLE genres_albums (genre_id BIGINT NOT NULL, album_id BIGINT NOT NULL, PRIMARY KEY(genre_id, album_id))');
        $this->addSql('CREATE INDEX IDX_988D442D4296D31F ON genres_albums (genre_id)');
        $this->addSql('CREATE INDEX IDX_988D442D1137ABCF ON genres_albums (album_id)');
        $this->addSql('CREATE TABLE genres_artists (genre_id BIGINT NOT NULL, album_id BIGINT NOT NULL, PRIMARY KEY(genre_id, album_id))');
        $this->addSql('CREATE INDEX IDX_CB03EF694296D31F ON genres_artists (genre_id)');
        $this->addSql('CREATE INDEX IDX_CB03EF691137ABCF ON genres_artists (album_id)');
        $this->addSql('CREATE TABLE lyric (id BIGINT NOT NULL, content TEXT NOT NULL, meaning TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE song (id BIGINT NOT NULL, album_id BIGINT DEFAULT NULL, lyrics_id BIGINT DEFAULT NULL, spotify_url VARCHAR(255) DEFAULT NULL, spotify_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, spotify_api_uri VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_33EDEEA11137ABCF ON song (album_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_33EDEEA185556DF8 ON song (lyrics_id)');
        $this->addSql('CREATE TABLE songs_artists (song_id BIGINT NOT NULL, artist_id BIGINT NOT NULL, PRIMARY KEY(song_id, artist_id))');
        $this->addSql('CREATE INDEX IDX_D382469CA0BDB2F3 ON songs_artists (song_id)');
        $this->addSql('CREATE INDEX IDX_D382469CB7970CF8 ON songs_artists (artist_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE artists_albums ADD CONSTRAINT FK_144789E2B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE artists_albums ADD CONSTRAINT FK_144789E21137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE genres_albums ADD CONSTRAINT FK_988D442D4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE genres_albums ADD CONSTRAINT FK_988D442D1137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE genres_artists ADD CONSTRAINT FK_CB03EF694296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE genres_artists ADD CONSTRAINT FK_CB03EF691137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE song ADD CONSTRAINT FK_33EDEEA11137ABCF FOREIGN KEY (album_id) REFERENCES album (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE song ADD CONSTRAINT FK_33EDEEA185556DF8 FOREIGN KEY (lyrics_id) REFERENCES lyric (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE songs_artists ADD CONSTRAINT FK_D382469CA0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE songs_artists ADD CONSTRAINT FK_D382469CB7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE album_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE artist_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE genre_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE lyric_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE song_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE artists_albums DROP CONSTRAINT FK_144789E2B7970CF8');
        $this->addSql('ALTER TABLE artists_albums DROP CONSTRAINT FK_144789E21137ABCF');
        $this->addSql('ALTER TABLE genres_albums DROP CONSTRAINT FK_988D442D4296D31F');
        $this->addSql('ALTER TABLE genres_albums DROP CONSTRAINT FK_988D442D1137ABCF');
        $this->addSql('ALTER TABLE genres_artists DROP CONSTRAINT FK_CB03EF694296D31F');
        $this->addSql('ALTER TABLE genres_artists DROP CONSTRAINT FK_CB03EF691137ABCF');
        $this->addSql('ALTER TABLE song DROP CONSTRAINT FK_33EDEEA11137ABCF');
        $this->addSql('ALTER TABLE song DROP CONSTRAINT FK_33EDEEA185556DF8');
        $this->addSql('ALTER TABLE songs_artists DROP CONSTRAINT FK_D382469CA0BDB2F3');
        $this->addSql('ALTER TABLE songs_artists DROP CONSTRAINT FK_D382469CB7970CF8');
        $this->addSql('DROP TABLE album');
        $this->addSql('DROP TABLE artist');
        $this->addSql('DROP TABLE artists_albums');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE genres_albums');
        $this->addSql('DROP TABLE genres_artists');
        $this->addSql('DROP TABLE lyric');
        $this->addSql('DROP TABLE song');
        $this->addSql('DROP TABLE songs_artists');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
