# import necessary modules
import re  # for regular expression matching
import sys  # for working with command line arguments

# check if an input file was provided as a command line argument
if len(sys.argv) < 2:
    print("Usage: python batting_average.py <input_file>")
    sys.exit(1)

# regular expression pattern to extract player name, at-bats, and hits from each line
pattern = r'^(.*) batted (\d+) times with (\d+) hits and (\d+) runs$'

# create an empty dictionary to store each player's at-bats and hits
player_stats = {}

# open the input file and process each line
with open(sys.argv[1], 'r') as f:
    for line in f:
        # match the line with the regular expression pattern
        match = re.match(pattern, line)
        if match:
            # extract player name, at-bats, and hits from the line
            player_name = match.group(1)
            at_bats = int(match.group(2))
            hits = int(match.group(3))
            # add player's stats to the dictionary or update if player already exists
            player_stats[player_name] = player_stats.get(player_name, [0, 0])
            player_stats[player_name][0] += at_bats
            player_stats[player_name][1] += hits

# create a new dictionary to store each player's batting average
batting_averages = {}

# compute batting average for each player with at least one at-bat and store in the new dictionary
for player_name, stats in player_stats.items():
    at_bats, hits = stats
    if at_bats > 0:
        batting_average = hits / at_bats
        batting_averages[player_name] = batting_average

# sort the players by batting average in descending order and print the results
for player_name, batting_average in sorted(batting_averages.items(), key=lambda x: x[1], reverse=True):
    print(f"{player_name}: {batting_average:.3f}")
