import sys, os
import re, math

#Regular Expression function
score_regex = re.compile(r"\b([\w]+ [\w]+) batted ([\d]{1,2}) times with ([\d]{1,2}) hits and ([\d]{1,2}) runs\b")
def data_from_string(str):
    match = score_regex.match(str)
    if match is not None:
        return (match.group(1), int(match.group(2)), int(match.group(3)) )
    else:
        return False

#define player class
class Player:
    def __init__(self, name, bats, hits):
        self.name = name
        self.bats = bats
        self.hits = hits
    
    def update(self, bats, hits):
        self.bats += bats
        self.hits += hits
        self.rate = round(float(self.hits)/float(self.bats), 3)
    
    #def __repr__(self):
     #   return '{}: {:.3f}'.format(self.name, self.rate)
        
    #def cal(self):
     #   result = float(bats)/float(bats)
      #  return result
        
def getrate(player):
    return player.rate

#############################        
# start of the program         
#############################       
if len(sys.argv) < 2:
    sys.exit("Usage: %s filename" % sys.argv[0])
    
filename = sys.argv[1]

if not os.path.exists(filename):
	sys.exit("Error: File '%s' not found" % sys.argv[1])

palyer_count = 0
player_list = []
find_flag = 0        
f = open(filename)

for line in f:
    if data_from_string(line) == False:
        continue
    else :
        name, bats, hits = data_from_string(line)
        for player in player_list :
            if player.name == name :
                find_flag = 1
                player.update(bats,hits)
        if find_flag == 0 :
            player_list.append(Player(name, bats, hits) )
            
        find_flag = 0

sorted_list = sorted(player_list, key=getrate, reverse=True)

for player in sorted_list :
    print "{0}: {1:.3f}".format(player.name, player.rate)
                