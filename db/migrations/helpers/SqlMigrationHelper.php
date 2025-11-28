<?php

declare(strict_types=1);

/**
 * SqlMigrationHelper
 *
 * Helper to split SQL files into executable statements for Phinx migrations.
 *
 * Usage:
 *   require_once __DIR__ . '/helpers/SqlMigrationHelper.php';
 *   $sql = file_get_contents(__DIR__ . '/../../sql/migrations/002_procedures.sql');
 *   $statements = SqlMigrationHelper::splitSql($sql);
 *   foreach ($statements as $stmt) {
 *       $this->execute($stmt);
 *   }
 *
 * Or:
 *   $statements = SqlMigrationHelper::splitSqlFile($sqlFilePath);
 */
if (!class_exists("SqlMigrationHelper", false)) {
    final class SqlMigrationHelper
    {
        private const SPLIT_TOKEN = "-- @@SPLIT@@";

        /**
         * Read a SQL file and split into executable statements.
         *
         * @param string $filePath
         * @return string[]
         */
        public static function splitSqlFile(string $filePath): array
        {
            $sql = @file_get_contents($filePath);
            if ($sql === false) {
                throw new \RuntimeException(
                    "No se pudo leer el archivo SQL: $filePath",
                );
            }

            return self::splitSql($sql);
        }

        /**
         * Split a SQL string into an array of statements ready for execution.
         *
         * This will:
         * - detect DELIMITER blocks and treat them as stored-procedure/function definitions
         *   (keeping them intact),
         * - for regular SQL without DELIMITER, split by semicolons.
         *
         * Returned statements always end with a semicolon.
         *
         * @param string $sql
         * @return string[]
         */
        public static function splitSql(string $sql): array
        {
            // Remove potential UTF-8 BOM and normalize newlines.
            $sql = preg_replace('/^\xEF\xBB\xBF/', "", $sql);
            $sql = str_replace(["\r\n", "\r"], PHP_EOL, $sql);

            if (preg_match("/^\s*DELIMITER\s+/mi", $sql)) {
                return self::splitWithDelimiters($sql);
            }

            return self::splitBySemicolons($sql);
        }

        /**
         * Split SQL that uses DELIMITER blocks (procedures/functions)
         *
         * @param string $sql
         * @return string[]
         */
        private static function splitWithDelimiters(string $sql): array
        {
            // Find the first non-semicolon delimiter (e.g. '//', '$$') or fallback to //
            preg_match_all('/^\s*DELIMITER\s+(\S+)\s*$/mi', $sql, $matches);
            $delimiters = $matches[1] ?? [];
            $blockDelimiter = null;
            foreach ($delimiters as $d) {
                $dTrim = trim($d);
                if ($dTrim !== ";") {
                    $blockDelimiter = $dTrim;
                    break;
                }
            }
            if ($blockDelimiter === null) {
                $blockDelimiter = "//";
            }

            // Remove explicit DELIMITER lines
            $sql = preg_replace('/^\s*DELIMITER\s+\S+\s*$/mi', "", $sql);

            // Mark block ends (END <delimiter>) as safe split points:
            $escaped = preg_quote($blockDelimiter, "/");
            $sql = preg_replace(
                "/END\\s*{$escaped}\\s*/mi",
                "END; " . self::SPLIT_TOKEN . PHP_EOL,
                $sql,
            );

            // Ensure DROP PROCEDURE/FUNCTION lines are recognized as split points as well
            $sql = preg_replace(
                '/^(DROP\\s+(?:PROCEDURE|FUNCTION)\\s+IF\\s+EXISTS\\s+[\\w`]+\\s*;)\\s*$/mi',
                '$1 ' . self::SPLIT_TOKEN,
                $sql,
            );

            // Split on the stable token
            $parts = array_map("trim", explode(self::SPLIT_TOKEN, $sql));
            $parts = array_filter($parts, fn($p) => $p !== "");

            $statements = [];
            foreach ($parts as $part) {
                $part = rtrim($part);
                if ($part === "") {
                    continue;
                }

                // If after removing all leading comments there is no SQL left, skip.
                // We only want to skip true comment-only parts. However, if a part
                // begins with comments and contains SQL later, we preserve the whole part
                // (including its leading comments).
                $withoutLeading = self::removeLeadingComments($part);
                if (trim($withoutLeading) === "") {
                    continue; // purely comments
                }

                // Make sure the statement ends with a semicolon
                if (substr($part, -1) !== ";") {
                    $part .= ";";
                }
                $statements[] = $part;
            }

            return $statements;
        }

        /**
         * Split SQL by semicolons (naive)
         *
         * @param string $sql
         * @return string[]
         */
        private static function splitBySemicolons(string $sql): array
        {
            $parts = array_map("trim", explode(";", $sql));
            $parts = array_filter($parts, fn($p) => $p !== "");

            $statements = [];
            foreach ($parts as $part) {
                $part = rtrim($part);
                if ($part === "") {
                    continue;
                }

                // Remove leading comments and test whether anything remains;
                // if nothing remains, the part is comment-only and should be skipped.
                $withoutLeading = self::removeLeadingComments($part);
                if (trim($withoutLeading) === "") {
                    continue;
                }

                // Re-append semicolon for the SQL executor
                if (substr($part, -1) !== ";") {
                    $part .= ";";
                }
                $statements[] = $part;
            }

            return $statements;
        }

        /**
         * Remove leading comments (single-line -- / # and block /* ... *\/) from the given string.
         *
         * NOTE: this only removes comments at the beginning of the part; inline comments
         * inside the statement are left intact. This is used only to detect whether a
         * split part is comment-only or there's actual SQL to execute.
         *
         * @param string $text
         * @return string
         */
        private static function removeLeadingComments(string $text): string
        {
            $s = $text;

            // Remove initial block comments (/* ... */)
            // Use a loop in case there are multiple leading block comments
            do {
                $before = $s;
                $s = preg_replace("/^\s*\/\*[\s\S]*?\*\/\s*/", "", $s);
            } while ($s !== $before);

            // Remove leading single-line comments (-- or #) and blank lines at the start of the string.
            // This allows cases where comments are mixed with blank lines before SQL code:
            // - Lines like "-- comment" or "#" will be removed if there is nothing else on the part.
            // - Blank lines are also considered part of the leading-comment region.
            // The regex strips one or more combinations of comment lines and blank lines.
            $s = preg_replace(
                '/^\s*(?:(?:--|#)[^\r\n]*(?:\r?\n|$)|\r?\n|\/\*[\s\S]*?\*\/\s*)+/s',
                "",
                $s,
            );

            return $s;
        }
    }
}
