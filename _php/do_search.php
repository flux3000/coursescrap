<?php
//do actual implementation

/*
	The structure is
	search:
		[name]
	related:
		[name,score]
	results:
		[name,instructor,description,tags[name,score]]
	//A useful tool for parsing json can be found here: http://json.parser.online.fr/
*/
?>
{"search":[{"name":"info"},{"name":"design"}],"related":[{"name":"java", "score":4},{"name":"objective C", "score":2}, {"name":"computer", "score":1}, {"name":"law", "score":1}, {"name":"science","score":1}, {"name":"prototyping", "score":1}],"results":[{"name":"Information Law and Policy", "instructor":"Dierdre Mulligan", "description": "Three hours of lecture per week. Law is one of a number of policies that mediates the tension between free flow and restrictions on the flow of information. This course introduces students to copyright and other forms of legal protection for databases, licensing of information, consumer protection, liability for insecure systems and defective information, privacy, and national and international information policy. NOTE: Before Fall 2010, this course was offered for 2 units.", "tags":[{"name":"info", "score":3}, {"name":"design", "score":1}]},{"name":"Intellectual Property Law for the Information Industries", "instructor":"Brian Carver", "description": "Three hours of lecture per week. This course will provide an overview of the intellectual property laws with which information managers need to be familiar. It will start with a consideration of trade secrecy law that information technology and other firms routinely use to protect commercially valuable information. It will then consider the role that copyright law plays in the legal protection of information products and services. Although patents for many years rarely were available to protect information innovations, patents on such innovations are becoming increasingly common. As a consequence, it is necessary to consider standards of patentability and the scope of protection that patent affords to innovators. Trademark law allows firms to protect words or symbols used to identify their goods or services and to distinguish them from the goods and services of other producers. It offers significant protection to producers of information products and services. Because so many firms license intellectual property rights, some coverage of licensing issues is also important. Much of the course will concern the legal protection of computer software and databases, but it will also explore some intellectual property issues arising in cyberspace.", "tags":[{"name":"info", "score":3}, {"name":"design", "score":1, "law":1}]}]}