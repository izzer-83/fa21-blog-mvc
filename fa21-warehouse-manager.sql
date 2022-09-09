-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 09. Sep 2022 um 08:58
-- Server-Version: 10.4.24-MariaDB
-- PHP-Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `fa21-warehouse-manager`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `article`
--

CREATE TABLE `article` (
  `articleID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `minQuantity` int(11) NOT NULL DEFAULT 0,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `article`
--

INSERT INTO `article` (`articleID`, `name`, `description`, `quantity`, `minQuantity`, `createdAt`) VALUES
(1, 'Kaliumkarbonat', 'K2CO3', 9, 10, '2022-09-04 12:30:30'),
(4, 'Diamanten', 'Echte Diamanten', 100, 1, '2022-09-06 11:44:28'),
(5, 'Kohle', 'Zum heizen', 100, 10, '2022-09-08 11:04:38'),
(6, 'Kohle', 'Mehr Kohle', 100, 10, '2022-09-08 11:05:14'),
(7, 'Wasser', 'Zum trinken', 1000, 100, '2022-09-08 11:07:24'),
(8, 'Bier', 'Schmeckt gut ', 100, 1, '2022-09-08 11:07:40'),
(9, 'Kabel', 'Kupferkabel', 15, 5, '2022-09-08 11:09:12'),
(10, 'Mehl', 'Zum backen', 100, 10, '2022-09-08 14:40:39');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `publicID` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `roleID` int(1) NOT NULL DEFAULT 3,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`userID`, `publicID`, `username`, `password`, `email`, `roleID`, `createdAt`) VALUES
(3, 'd7ff2ecc-e3e5-41ae-d061-e25eb17dde39', 'admin', '$2y$10$L84sVatgY.ri4Vezf9oYfOR7G4J9CB/c5GQ/FKqFina08VX./OrOu', 'thomas@ist-gott.de', 1, '2022-08-28 01:17:06'),
(5, '4fe3765d-9d56-43e4-f7c8-ad1ed52852a2', 'thomas', '$2y$10$j8EqDdJF8ArxuZlk9g6iq.MJRTtbKjncjya.cYFw2aDVxHYx9Kiwq', 't.klotz@gmx.net', 2, '2022-08-28 16:40:19'),
(8, '4d2d96b1-e663-4169-cfb8-0b70d1cb3131', 'try1', '$2y$10$VPKUbs2Yi/lKUs6u4AdbdOvzqZ/gRUpWc4kc6Vd4KNfcc36SPDew6', 'try@hard.de', 3, '2022-08-30 11:24:34'),
(9, '401865bb-f70e-44ce-8bb4-188db67822f5', 'moderator', '$2y$10$PWWqWf6jy26BIMtVq.XQ1eVdHhUTdhRtKlx8xF6IcZ6PWdBBq1QLC', 'mod@mod.de', 2, '2022-09-07 08:24:19'),
(10, '1af81782-a4e3-4abd-c473-17e33b693570', 'user', '$2y$10$a.M4tkbV4p36SEDatpy3eeN7XQP6x.RfatVG6oZVNfVYSyEcjWj5K', 'user@user.de', 3, '2022-09-07 08:47:20');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_role`
--

CREATE TABLE `user_role` (
  `user_roleID` int(11) NOT NULL,
  `rolename` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `user_role`
--

INSERT INTO `user_role` (`user_roleID`, `rolename`, `description`) VALUES
(1, 'Admin', 'This role is for Administrators of this site.'),
(2, 'Manager', 'This role is for Managers of this site.'),
(3, 'User', 'Standard-User');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `warehouse`
--

CREATE TABLE `warehouse` (
  `warehouseID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `maxQuantity` int(10) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `warehouse`
--

INSERT INTO `warehouse` (`warehouseID`, `name`, `description`, `maxQuantity`, `createdAt`) VALUES
(4, 'Lager 1', 'Feststofflager', 5000, '2022-09-03 21:03:17'),
(5, 'Lager 2', 'Flüssiglager', 3000, '2022-09-03 21:03:33'),
(6, 'Lager 3', 'Test', 1000, '2022-09-03 21:04:42');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `warehouse_manager`
--

CREATE TABLE `warehouse_manager` (
  `managerID` int(11) NOT NULL,
  `articleID` int(11) NOT NULL,
  `warehouseID` int(11) NOT NULL,
  `createdBy` int(11) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `warehouse_manager`
--

INSERT INTO `warehouse_manager` (`managerID`, `articleID`, `warehouseID`, `createdBy`, `createdAt`) VALUES
(1, 1, 4, 3, '2022-09-04 12:36:57'),
(2, 4, 5, 3, '2022-09-06 11:44:28'),
(3, 5, 6, 3, '2022-09-08 11:04:38'),
(4, 6, 4, 3, '2022-09-08 11:05:14'),
(5, 7, 5, 3, '2022-09-08 11:07:24'),
(6, 8, 5, 3, '2022-09-08 11:07:40'),
(7, 9, 6, 3, '2022-09-08 11:09:12'),
(8, 10, 4, 3, '2022-09-08 14:40:39');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`articleID`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `publicID` (`publicID`);

--
-- Indizes für die Tabelle `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`user_roleID`);

--
-- Indizes für die Tabelle `warehouse`
--
ALTER TABLE `warehouse`
  ADD PRIMARY KEY (`warehouseID`);

--
-- Indizes für die Tabelle `warehouse_manager`
--
ALTER TABLE `warehouse_manager`
  ADD PRIMARY KEY (`managerID`),
  ADD KEY `articleID` (`articleID`),
  ADD KEY `warehouseID` (`warehouseID`),
  ADD KEY `createdBy` (`createdBy`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `article`
--
ALTER TABLE `article`
  MODIFY `articleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT für Tabelle `user_role`
--
ALTER TABLE `user_role`
  MODIFY `user_roleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `warehouse`
--
ALTER TABLE `warehouse`
  MODIFY `warehouseID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT für Tabelle `warehouse_manager`
--
ALTER TABLE `warehouse_manager`
  MODIFY `managerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
