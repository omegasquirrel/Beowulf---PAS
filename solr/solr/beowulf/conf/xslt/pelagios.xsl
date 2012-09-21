<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:xs="http://www.w3.org/2001/XMLSchema" exclude-result-prefixes="xs xsl" version="2.0"
	xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:oac="http://www.openannotation.org/ns/" xmlns:owl="http://www.w3.org/2002/07/owl#" xmlns:foaf="http://xmlns.com/foaf/0.1/">
	<xsl:output method="xml" encoding="UTF-8" indent="yes"/>

	<xsl:template match="/">
		<rdf:RDF>
			<xsl:apply-templates select="response/result/doc"/>
		</rdf:RDF>
	</xsl:template>

	<xsl:template match="doc">
		<oac:Annotation rdf:about="http://finds.org.uk/rdf/pelagios.rdf#{str[@name='old_findID']}">
			<dcterms:title>
				<xsl:choose>
				<xsl:when test="str[@name='rulerName']"> 
			    	<xsl:value-of select="str[@name='old_findID']"/>: A coin issued by <xsl:value-of select="str[@name='rulerName']"/>
			    </xsl:when> 
     		     <xsl:otherwise>
					<xsl:value-of select="str[@name='old_findID']"/>: A coin with an unrecorded/uncertain issuer.</xsl:otherwise> 
			 	</xsl:choose>
			</dcterms:title>
			<xsl:for-each select="int[@name='pleiadesID']">
				<oac:hasBody rdf:resource="http://pleiades.stoa.org/places/{.}#this"/>
			</xsl:for-each>
			<owl:sameAs rdf:resource="http://nomisma.org/id/{str[@name='mintNomisma']}"/>
			<oac:hasTarget rdf:resource="http://finds.org.uk/database/artefacts/record/id/{int[@name='id']}"/>
			<xsl:choose>
				<xsl:when test="int[@name='thumbnail']"> 
				<foaf:thumbnail>http://finds.org.uk/images/thumbnails/<xsl:value-of select="int[@name='thumbnail']"/>.jpg</foaf:thumbnail>
				</xsl:when>
			</xsl:choose>
		</oac:Annotation>
	</xsl:template>
</xsl:stylesheet>