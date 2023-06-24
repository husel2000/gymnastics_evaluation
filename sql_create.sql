-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 24. Jun 2023 um 22:12
-- Server-Version: 10.5.19-MariaDB-0+deb11u2
-- PHP-Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `wettkampf`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `riegenliste`
--

CREATE TABLE `riegenliste` (
  `id_riegenliste` int(10) UNSIGNED NOT NULL,
  `riegentext` varchar(99) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `riegenliste_liste`
--

CREATE TABLE `riegenliste_liste` (
  `id_riegenliste_liste` int(10) UNSIGNED NOT NULL,
  `id_riegenliste` int(10) UNSIGNED NOT NULL,
  `riege_no` int(10) UNSIGNED NOT NULL,
  `reihenfolge` int(10) UNSIGNED NOT NULL,
  `id_wettkampf` int(10) UNSIGNED NOT NULL,
  `id_turner` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `riegenliste_wettkampf`
--

CREATE TABLE `riegenliste_wettkampf` (
  `id_riegenliste_wettkampf` int(10) UNSIGNED NOT NULL,
  `id_riegenliste` int(10) UNSIGNED NOT NULL,
  `id_wettkampf` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `turner`
--

CREATE TABLE `turner` (
  `id_turner` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `vorname` varchar(100) NOT NULL,
  `geschlecht` enum('m','w') NOT NULL,
  `verein` varchar(100) DEFAULT NULL COMMENT 'Heimatverein',
  `pass` varchar(100) DEFAULT NULL,
  `pass_gueltig` date DEFAULT NULL,
  `geburtsdatum` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `urkunde`
--

CREATE TABLE `urkunde` (
  `id_urkunde` int(10) UNSIGNED NOT NULL,
  `titel` varchar(50) NOT NULL,
  `code` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `wettkampf`
--

CREATE TABLE `wettkampf` (
  `id_wettkampf` int(10) UNSIGNED NOT NULL,
  `datum` date NOT NULL,
  `bezeichnung` varchar(100) NOT NULL,
  `typ` enum('einzel','einzel_bereich') NOT NULL,
  `jahrgang_min` int(11) NOT NULL,
  `jahrgang_max` int(11) NOT NULL,
  `geschlecht` enum('m','w') DEFAULT NULL,
  `system` enum('lk','p','turn') NOT NULL,
  `opt_text1` varchar(100) DEFAULT NULL,
  `opt_text2` varchar(100) DEFAULT NULL,
  `opt_text3` varchar(100) DEFAULT NULL,
  `opt_text4` varchar(100) DEFAULT NULL,
  `opt_text5` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `wettkampf_geraet`
--

CREATE TABLE `wettkampf_geraet` (
  `id_wettkampf_geraet` int(10) UNSIGNED NOT NULL,
  `id_wettkampf` int(10) UNSIGNED NOT NULL,
  `reihenfolge` int(10) UNSIGNED NOT NULL,
  `bezeichnung` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `wettkampf_geraet_turner`
--

CREATE TABLE `wettkampf_geraet_turner` (
  `id_wettkampf_geraet_turner` int(11) UNSIGNED NOT NULL,
  `id_turner` int(11) UNSIGNED NOT NULL,
  `id_wettkampf_geraet` int(11) UNSIGNED NOT NULL,
  `wert_ausgang` double DEFAULT NULL,
  `wert_abzug` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `riegenliste`
--
ALTER TABLE `riegenliste`
  ADD PRIMARY KEY (`id_riegenliste`);

--
-- Indizes für die Tabelle `riegenliste_liste`
--
ALTER TABLE `riegenliste_liste`
  ADD PRIMARY KEY (`id_riegenliste_liste`),
  ADD KEY `id_riegenliste` (`id_riegenliste`),
  ADD KEY `id_wettkampf` (`id_wettkampf`),
  ADD KEY `id_turner` (`id_turner`);

--
-- Indizes für die Tabelle `riegenliste_wettkampf`
--
ALTER TABLE `riegenliste_wettkampf`
  ADD PRIMARY KEY (`id_riegenliste_wettkampf`),
  ADD KEY `id_riegenliste` (`id_riegenliste`),
  ADD KEY `id_wettkampf` (`id_wettkampf`);

--
-- Indizes für die Tabelle `turner`
--
ALTER TABLE `turner`
  ADD PRIMARY KEY (`id_turner`);

--
-- Indizes für die Tabelle `urkunde`
--
ALTER TABLE `urkunde`
  ADD PRIMARY KEY (`id_urkunde`),
  ADD UNIQUE KEY `titel` (`titel`);

--
-- Indizes für die Tabelle `wettkampf`
--
ALTER TABLE `wettkampf`
  ADD PRIMARY KEY (`id_wettkampf`),
  ADD UNIQUE KEY `datum` (`datum`,`bezeichnung`);

--
-- Indizes für die Tabelle `wettkampf_geraet`
--
ALTER TABLE `wettkampf_geraet`
  ADD PRIMARY KEY (`id_wettkampf_geraet`),
  ADD UNIQUE KEY `id_wettkampf` (`id_wettkampf`,`reihenfolge`);

--
-- Indizes für die Tabelle `wettkampf_geraet_turner`
--
ALTER TABLE `wettkampf_geraet_turner`
  ADD PRIMARY KEY (`id_wettkampf_geraet_turner`),
  ADD KEY `id_wettkampf_geraet` (`id_wettkampf_geraet`),
  ADD KEY `id_turner` (`id_turner`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `riegenliste`
--
ALTER TABLE `riegenliste`
  MODIFY `id_riegenliste` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `riegenliste_liste`
--
ALTER TABLE `riegenliste_liste`
  MODIFY `id_riegenliste_liste` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `riegenliste_wettkampf`
--
ALTER TABLE `riegenliste_wettkampf`
  MODIFY `id_riegenliste_wettkampf` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `turner`
--
ALTER TABLE `turner`
  MODIFY `id_turner` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `urkunde`
--
ALTER TABLE `urkunde`
  MODIFY `id_urkunde` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `wettkampf`
--
ALTER TABLE `wettkampf`
  MODIFY `id_wettkampf` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `wettkampf_geraet`
--
ALTER TABLE `wettkampf_geraet`
  MODIFY `id_wettkampf_geraet` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `wettkampf_geraet_turner`
--
ALTER TABLE `wettkampf_geraet_turner`
  MODIFY `id_wettkampf_geraet_turner` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
