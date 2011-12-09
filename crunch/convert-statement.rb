=begin
Ruby script convert a santander csv statement into a format
to be imported into crunch.co.uk accounting system

First ever Ruby Script! Woo!

Usage:
ruby convert-statement.rb statements/2011-10.csv converted/converted.csv
=end

#arguments
in_file = ARGV[0]

# read the file in
aFile = File.new(in_file)
lines = aFile.readlines
lines.pop # remove last line

statement = Hash.new()
lines.each {| line |

  line.chomp!
  cells = line.split(',')
  cells.shift # remove the first cell

  # format the date
  entry_date = cells[0].split('/')
  entry_date = entry_date.reverse!
  entry_date.join('-')

  #puts entry_date.join('-')
  statement[entry_date.join('-')] = cells
}

# header
puts ['Date','Reference','Amount','Balance',].join(',')

# sort the statement
statement.keys.sort.each {| entry_date |
  print statement[entry_date].join(',') + "\n"
}