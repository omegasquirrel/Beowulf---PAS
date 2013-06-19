<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
                xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
                xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
                xmlns:owl="http://www.w3.org/2002/07/owl#"
                xmlns:crm="http://erlangen-crm.org/101001/"
                xmlns:crmeh="http://purl.org/crmeh#"
                xmlns:claros="http://purl.org/NET/Claros/vocab#"
                xmlns:arch="http://ochre.lib.uchicago.edu/schema/SpatialUnit/SpatialUnit.xsd"
                xmlns:dc="http://purl.org/dc/elements/1.1/"
                xmlns:oac="http://www.openannotation.org/ns/"
                xmlns:dcterms="http://purl.org/dc/terms/"
		xmlns:skos="http://www.w3.org/2004/02/skos/core#" 
     		xmlns:skosxl="http://www.w3.org/2008/05/skos-xl#" 
     		xmlns:google="http://rdf.data-vocabulary.org/#"
		xmlns:con="http://www.w3.org/2000/10/swap/pim/contact#"
				xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#"
				xmlns:c="http://s.opencalais.com/1/pred/"
				xmlns:bibo="http://purl.org/ontology/bibo/"
				xmlns:units="http://qudt.org/"
				xmlns:og="http://ogp.me/ns#"
				xmlns:nm="http://nomisma.org/id/"
				xmlns:ns2="http://collection.britishmuseum.org/id/crm/bm-extensions/" 
				xmlns:foaf="http://xmlns.com/foaf/0.1/"
				xmlns:cc="http://creativecommons.org/ns#"
				xmlns:gn="http://www.geonames.org/ontology#" 
				xmlns:geoplanet="http://where.yahooapis.com/v1/place/"
				xml:lang="en"
                >
                
    <xsl:output method="xml" indent="yes" encoding="utf-8" />

	<xsl:param name="url">
		<xsl:value-of select="'http://finds.org.uk/database/artefacts/record/id/'" />
	</xsl:param> 
	
	<xsl:param name="denomUrl">
		<xsl:value-of select="'http://finds.org.uk/database/terminology/denominations/denomination/id/'" />
	</xsl:param>
	
	<xsl:param name="bmThes">
		<xsl:value-of select="'http://collection.britishmuseum.org/id/thesauri/'" />
	</xsl:param>
	
	<xsl:param name="thumb">
		<xsl:value-of select="'http://finds.org.uk/images/thumbnails/'" />
	</xsl:param>
	
	<xsl:param name="images">
		<xsl:value-of select="'http://finds.org.uk/images/'" />
	</xsl:param>
	
	<xsl:param name="nomismaUrl">
		<xsl:value-of select="'http://nomisma.org/id/'" />
	</xsl:param>
	
	<xsl:param name="language">
		<xsl:value-of select="'en'" />
	</xsl:param> 
	
	<xsl:template match="/">
		<rdf:RDF>
			<xsl:apply-templates select="//artefact" />
		</rdf:RDF>
	</xsl:template>
	
	
	<xsl:template match="//artefact">
	<foaf:Document>
	<xsl:attribute name="rdf:about"><xsl:value-of select="$url"/><xsl:value-of select="id"/></xsl:attribute> 
		<dcterms:contributor>
			<foaf:Person>
				<rdfs:label>Created by: </rdfs:label>
				<foaf:name><xsl:value-of select="fullname"/></foaf:name>
			</foaf:Person>
		</dcterms:contributor>
		
		<dcterms:contributor>
			<foaf:Person>
				<rdfs:label>Updated by: </rdfs:label>
				<foaf:name><xsl:value-of select="fullname"/></foaf:name>
			</foaf:Person>
		</dcterms:contributor>
		
		<dcterms:contributor>
			<foaf:Person>
				<rdfs:label>Identified by:</rdfs:label>
				<foaf:name><xsl:value-of select="fullname"/></foaf:name>
			</foaf:Person>
		</dcterms:contributor>
		
		<dcterms:contributor>
			<foaf:Person>
				<rdfs:label>Secondary identifier:</rdfs:label>
				<foaf:name><xsl:value-of select="fullname"/></foaf:name>
			</foaf:Person>
		</dcterms:contributor>
		
		<dcterms:description>
                    <xsl:attribute name="xsd:string"><xsl:value-of select="description"/></xsl:attribute>
                </dcterms:description>
		<dcterms:modified>
                    <xsl:attribute name="xsd:date"><xsl:value-of select="updated"/></xsl:attribute>
                </dcterms:modified>
		<dcterms:created>
                    <xsl:attribute name="xsd:date"><xsl:value-of select="created"/></xsl:attribute>
                </dcterms:created>
		<dcterms:coverage>
                    <xsl:attribute name="xsd:string">England and Wales</xsl:attribute>
                </dcterms:coverage>
		<dcterms:publisher>
                    <xsl:attribute name="xsd:string">Portable Antiquities Scheme</xsl:attribute>
                </dcterms:publisher>
		<cc:attributionName>
                    <xsl:attribute name="xsd:string">http://finds.org.uk</xsl:attribute>
                </cc:attributionName>
		<cc:attributionURL>
                    <xsl:attribute name="xsd:string">http://finds.org.uk</xsl:attribute>
                </cc:attributionURL>
		<cc:license>
                    <xsl:attribute name="xsd:date">http://creativecommons.org/licenses/by-sa/3.0/</xsl:attribute>
                </cc:license>		
		
	<foaf:primaryTopic>

		<crm:E22_Man-Made_Object>
			
			<!--  label -->
			<rdfs:label>
                            <xsl:attribute name="xsd:string"><xsl:value-of select="old_findID"/></xsl:attribute>
                        </rdfs:label>
			
			<!--  The object's title -->
			<crm:P102_has_title>
			    <crm:E35_Title>
			      <xsl:attribute name="xsd:string">Object record for: <xsl:value-of select="old_findID"/></xsl:attribute>
			    </crm:E35_Title>
			</crm:P102_has_title>
			
			<!-- The preferred identifier: Must exist only once--> 
			<crm:P48_has_preferred_identifier>
				<crm:E42_Identifier> 
                                    <xsl:attribute name="xsd:string"><xsl:value-of select="old_findID"/></xsl:attribute>
                                </crm:E42_Identifier>
			</crm:P48_has_preferred_identifier>
			
			<!--  object type appellation -->

			<crm:P2_has_type>
				<crm:E55_Type>
					<xsl:attribute name="xsd:string"><xsl:value-of select="objecttype"/></xsl:attribute>
					<rdfs:label>
                                            <xsl:attribute name="xsd:string">Object type:</xsl:attribute>
                                        </rdfs:label>
					<skos:inScheme><xsl:value-of select="$bmThes"/>id/thesauri/object</skos:inScheme>
                                        <owl:sameAs>
                                           <xsl:attribute name="rdf:resource">http://finds.org.uk/database/terminology/object/term/<xsl:value-of select="objecttype"/></xsl:attribute> 
                                        </owl:sameAs>
				</crm:E55_Type>
			</crm:P2_has_type>
			
			<crm:P12i_was_present_at>
					<ns2:EX_discovery>
						<crm:P2_has_type>
						<xsl:attribute name="rdf:resource"><xsl:value-of select="$bmThes"/>id/thesauri/find/E</xsl:attribute>
						</crm:P2_has_type>
						<crm:P7_took_place_at>
							<xsl:if test="knownas = ''">
							<crm:E47.Spatial_Coordinates>
								<claros:has_geoObject>
				        			<geo:Point>
							          <geo:lat rdf:datatype="xsd:decimal"><xsl:value-of select="fourFigureLat"/></geo:lat>
							          <geo:long rdf:datatype="xsd:decimal"><xsl:value-of select="fourFigureLon"/></geo:long>
							        </geo:Point>
				      			</claros:has_geoObject>
							</crm:E47.Spatial_Coordinates>
							</xsl:if>
						</crm:P7_took_place_at>
						<skos:prefLabel>
                                                    <xsl:attribute name="xsd:string">Excavated or found at:</xsl:attribute>
                                                </skos:prefLabel>
					</ns2:EX_discovery>
				
			</crm:P12i_was_present_at>
			
			<!--  Identifiers  -->
			<crm:P1_is_identified_by>
				<crm:E42_Identifier>
					<xsl:attribute name="xsd:string"><xsl:value-of select="secuid"/></xsl:attribute>
					<rdfs:label>Secure ID: <xsl:value-of select="secuid"/></rdfs:label>
				</crm:E42_Identifier>
			</crm:P1_is_identified_by>
			
			<crm:P1_is_identified_by>
				<crm:E42_Identifier>
                                    <xsl:attribute name="xsd:string"><xsl:value-of select="other_ref" /></xsl:attribute>
					<crm:P2_has_type>
					<xsl:attribute name="rdf:resource"><xsl:value-of select="$bmThes"/>thesauri/identifier/otherid</xsl:attribute>
					</crm:P2_has_type>
					<rdfs:label>Other reference numbers:</rdfs:label>
				</crm:E42_Identifier>
			</crm:P1_is_identified_by>
			
			<crm:P4_has_time_span>
					<crm:E52_Time-Span>
						<crm:P82a_begin_of_the_begin rdf:datatype="xsd:gYear"><xsl:value-of select="numdate1" /></crm:P82a_begin_of_the_begin>
						<crm:P82b_end_of_the_end rdf:datatype="xsd:gYear"><xsl:value-of select="numdate2" /></crm:P82b_end_of_the_end>
						<crm:P3_has_note rdf:datatype="xsd:string"><xsl:value-of select="numdate1" /> - <xsl:value-of select="numdate2" /></crm:P3_has_note>
						<rdfs:label>Date range for object:</rdfs:label>
					</crm:E52_Time-Span>
				</crm:P4_has_time_span>
	
			
			<xsl:if test="objecttype = 'COIN'">
			<!--  The denomination -->
			<crm:P43F.has_dimension>
                        <crm:E54.Dimension>
                        	<rdfs:label>Unit of currency</rdfs:label>
                        	<crm:P2_has_type>
                        	<xsl:attribute name="rdf:type">http://collection.britishmuseum.org/id/thesauri/dimension/currency</xsl:attribute>
							</crm:P2_has_type>
							<crm:P91_has_unit>
							<xsl:attribute name="rdf:resource"><xsl:value-of select="$denomUrl"/><xsl:value-of select="denominationID"/></xsl:attribute>
							</crm:P91_has_unit> 
							<rdfs:label rdf:datatype="xsd:string"><xsl:value-of select="denomination"/></rdfs:label>
							<owl:sameAs>
								<xsl:attribute name="rdf:resource">http://nomisma.org/id/<xsl:value-of select="nomismaDenomination"/></xsl:attribute>
							</owl:sameAs>
							<owl:sameAs>
								<xsl:attribute name="rdf:resource">http://collection.britishmuseum.org/id/thesauri/currency/<xsl:value-of select="bmDenomination"/></xsl:attribute>
							</owl:sameAs>
							<owl:sameAs>
								<xsl:attribute name="rdf:resource">http://dbpedia.org/page/<xsl:value-of select="dbpediaDenomination"/></xsl:attribute>
							</owl:sameAs>
							<crm:P3_has_note>Portable Antiquities currency term: <xsl:value-of select="denomination"/></crm:P3_has_note>
                        </crm:E54.Dimension>
            </crm:P43F.has_dimension>
            
            <!--  The mint -->
			
			
			<!--  The obverse and reverse -->
			<crm:P56_bears_feature>
				<crm:E25_Man-Made_Feature>
				<crm:P2_has_type>
				<xsl:attribute name="rdf:type"><xsl:value-of select="$bmThes"/>aspect/obverse</xsl:attribute>
				</crm:P2_has_type>
				<rdfs:label>Obverse description</rdfs:label>
				<ns2:PX.physical_description><xsl:value-of select="obverse_description"/></ns2:PX.physical_description>
				<owl:sameAs>
					<xsl:attribute name="rdf:resource">http://nomisma.org/id/obverse</xsl:attribute>
				</owl:sameAs>
				</crm:E25_Man-Made_Feature>      
			</crm:P56_bears_feature>
			
			<crm:P56_bears_feature>
				<crm:E25_Man-Made_Feature>
                                    <crm:P2_has_type>
                                        <xsl:attribute name="rdf:type"><xsl:value-of select="$bmThes"/>association/namedInscription</xsl:attribute>
                                    </crm:P2_has_type>
                                    <rdfs:label>Obverse legend</rdfs:label>
                                   
                                    <ns2:PX.physical_description><xsl:value-of select="obverse_inscription"/></ns2:PX.physical_description>
                                    <owl:sameAs>
                                            <xsl:attribute name="rdf:resource">http://nomisma.org/id/obverse</xsl:attribute>
                                    </owl:sameAs>
                                    
				</crm:E25_Man-Made_Feature>      
			</crm:P56_bears_feature>
			
			<crm:P56_bears_feature>
				<crm:E25_Man-Made_Feature>
				<crm:P2_has_type>
				<xsl:attribute name="rdf:type"><xsl:value-of select="$bmThes"/>aspect/reverse</xsl:attribute>
				</crm:P2_has_type>
				<rdfs:label>Reverse description</rdfs:label>
				<owl:sameAs>
					<xsl:attribute name="rdf:resource">http://nomisma.org/id/reverse</xsl:attribute>
				</owl:sameAs>
				<ns2:PX.physical_description><xsl:value-of select="reverse_description"/></ns2:PX.physical_description>
                </crm:E25_Man-Made_Feature>      
			</crm:P56_bears_feature>
			
			<crm:P56_bears_feature>
				<crm:E25_Man-Made_Feature>
				<crm:P2_has_type>
				<xsl:attribute name="rdf:type"><xsl:value-of select="$bmThes"/>inscription</xsl:attribute>
				</crm:P2_has_type>
				<rdfs:label>Reverse legend</rdfs:label>
				<ns2:PX.physical_description><xsl:value-of select="reverse_inscription"/></ns2:PX.physical_description>
				<owl:sameAs>
					<xsl:attribute name="rdf:resource">http://nomisma.org/id/reverse</xsl:attribute>
				</owl:sameAs>
                </crm:E25_Man-Made_Feature>      
			</crm:P56_bears_feature>
			</xsl:if>
			
			<!--  an object's inscription -->
			<xsl:if test="inscription != ''">
			<crm:P56_bears_feature>
				<crm:E25_Man-Made_Feature>
				<crm:P2_has_type>
				<xsl:attribute name="rdf:type"><xsl:value-of select="$bmThes"/>inscription</xsl:attribute>
				</crm:P2_has_type>
				<rdfs:label>Object inscription: </rdfs:label>
				<ns2:PX.physical_description><xsl:value-of select="inscription"/></ns2:PX.physical_description>
				</crm:E25_Man-Made_Feature>      
			</crm:P56_bears_feature>
			</xsl:if>
			
			
			<!--  Description -->
			<crm:E5_Event>
				<crm:P2_has_type>
					<crm:E55_Type>
						<xsl:value-of select="description"/>
					</crm:E55_Type>	
				</crm:P2_has_type>
			</crm:E5_Event>
			
			<!--  Image representations -->
			<xsl:if test="thumbnail != ''">
			<!--  Thumbnail -->
			<crm:P138i_has_representation>
				<crm:E38_Image>
				<xsl:attribute name="rdf:about"><xsl:value-of select="$thumb"/><xsl:value-of select="thumbnail"/>.jpg</xsl:attribute> 
					<rdfs:label>A thumbnail image of <xsl:value-of select="old_findID"/></rdfs:label>
					<crm:P2_has_type>
					<xsl:attribute name="rdf:resource">http://purl.org/NET/Claros/vocab#Thumbnail</xsl:attribute>
					</crm:P2_has_type>
				</crm:E38_Image>
			</crm:P138i_has_representation>
	
			<crm:P138i_has_representation>
				<crm:E38_Image>
				<xsl:attribute name="rdf:about"><xsl:value-of select="$images"/><xsl:value-of select="imagedir"/><xsl:value-of select="filename"/></xsl:attribute> 
					<rdfs:label>A fullsized image of <xsl:value-of select="old_findID"/></rdfs:label>
				</crm:E38_Image>
			</crm:P138i_has_representation>
			</xsl:if>
			
			<crm:P70i_is_documented_in>
				<xsl:attribute name="rdf:resource"><xsl:value-of select="$url"/><xsl:value-of select="id"/></xsl:attribute> 
			</crm:P70i_is_documented_in>
		

		<!--  Latitude and Longitude point -->
			<xsl:if test="knownas = ''">
			<crm:P87F.is_identified_by>
				<crm:E47.Spatial_Coordinates>
					<claros:has_geoObject>
				        <geo:Point>
				          <geo:lat><xsl:value-of select="fourFigureLat"/></geo:lat>
				          <geo:long><xsl:value-of select="fourFigureLon"/></geo:long>
				        </geo:Point>
				        
				      </claros:has_geoObject>
				</crm:E47.Spatial_Coordinates>
				
			</crm:P87F.is_identified_by>
			</xsl:if>	
			
		<!--  location of find -->

		
		<xsl:if test="currentLocation != ''">
		<crm:P55F.has_current_location>
                    <crm:P87_is_identified_by>
                        <crm:E53.Place>
                            <xsl:attribute name="xsd:string"><xsl:value-of select="currentLocation"/></xsl:attribute>
                        </crm:E53.Place>
                    </crm:P87_is_identified_by>
		</crm:P55F.has_current_location>
		</xsl:if>
		
                
                <crm:P45_consists_of>
                    <crm:E57_Material>
                        <xsl:attribute name="rdf:about">http://finds.org.uk/database/terminology/material/id/<xsl:value-of select="primaryMaterial"/></xsl:attribute>
                        <skos:prefLabel><xsl:value-of select="primaryMaterial"/></skos:prefLabel>
                            <owl:sameAs>
                               <xsl:attribute name="rdf:resource">http://collection.britishmuseum.org/id/thesauri/x??????</xsl:attribute>  
                            </owl:sameAs>
                    </crm:E57_Material>
                </crm:P45_consists_of>
                
		
		<!--  Width -->
		<xsl:if test="width != ''">
			<crm:P43F.has_dimension>
                        <crm:E54.Dimension>
                        <xsl:attribute name="rdf:type"><xsl:value-of select="$bmThes"/>dimension/width</xsl:attribute> 
                            <crm:P91F.has_unit rdf:resource="http://qudt.org/vocab/unit#Millimeter" />
                                <crm:P90F.has_value>
                                    <xsl:attribute name="xsd:double"><xsl:value-of select="width"/></xsl:attribute> 
                                </crm:P90F.has_value>
                            <rdfs:label>Width</rdfs:label>
                            <crm:P3_has_note><xsl:value-of select="width"/></crm:P3_has_note>
                        </crm:E54.Dimension>
                    </crm:P43F.has_dimension>
         </xsl:if>
                    
		<!--  Diameter  -->
		<xsl:if test="diameter != ''">
			<crm:P43F.has_dimension>
                            <crm:E54.Dimension>
                        	<crm:P2_has_type>
                            <xsl:attribute name="rdf:type"><xsl:value-of select="$bmThes"/>dimension/diameter</xsl:attribute>
                            </crm:P2_has_type> 
                            <crm:P91F.has_unit rdf:resource="http://qudt.org/vocab/unit#Millimeter" />
                            <crm:P90F.has_value>
                                <xsl:attribute name="xsd:double"><xsl:value-of select="diameter"/></xsl:attribute> 
                            </crm:P90F.has_value>
                            <crm:P3_has_note><xsl:value-of select="diameter"/></crm:P3_has_note>
                            <rdfs:label>Diameter</rdfs:label>
                        </crm:E54.Dimension>
                    </crm:P43F.has_dimension>
        </xsl:if>         
		
		<!--  Height  -->
		<xsl:if test="height != ''">
					<crm:P43F.has_dimension>
                        <crm:E54.Dimension>
	                        <crm:P2_has_type>
	                        <xsl:attribute name="rdf:type"><xsl:value-of select="$bmThes"/>dimension/height</xsl:attribute>
	                        </crm:P2_has_type> 
                            <crm:P91F.has_unit rdf:resource="http://qudt.org/vocab/unit#Millimeter"/>
                            <crm:P90F.has_value>
                                <xsl:attribute name="xsd:double"><xsl:value-of select="height"/></xsl:attribute> 
                            </crm:P90F.has_value>
                            <crm:P3_has_note><xsl:value-of select="height"/></crm:P3_has_note>
                            <rdfs:label>Height</rdfs:label>
                        </crm:E54.Dimension>
                    </crm:P43F.has_dimension>
        </xsl:if>
        
		<!--  Thickness  -->
		<xsl:if test="thickness != ''">
					<crm:P43F.has_dimension>
                        <crm:E54.Dimension>
                        	<crm:P2_has_type>
                        	  <xsl:attribute name="rdf:type"><xsl:value-of select="$bmThes"/>dimension/thickness</xsl:attribute>
                        	</crm:P2_has_type>
                            <crm:P91F.has_unit rdf:resource="http://qudt.org/vocab/unit#Millimeter" />
                            <crm:P90F.has_value>
                                <xsl:attribute name="xsd:double"><xsl:value-of select="thickness"/></xsl:attribute> 
                            </crm:P90F.has_value>
                            <crm:P3_has_note><xsl:value-of select="thickness"/></crm:P3_has_note>
                            <rdfs:label>Thickness</rdfs:label>
                        </crm:E54.Dimension>
                    </crm:P43F.has_dimension>
         </xsl:if>   
                         
         <!--  Weight  -->
		<xsl:if test="weight != ''">
		<crm:P43F.has_dimension>
                        <crm:E54.Dimension>
                        	<crm:P2_has_type> 
                        	<xsl:attribute name="rdf:type"><xsl:value-of select="$bmThes"/>dimension/weight</xsl:attribute>
	                      	</crm:P2_has_type>
                            <crm:P91F.has_unit rdf:resource="http://qudt.org/vocab/unit#Gram" />
                            <crm:P90F.has_value>
                                	<xsl:attribute name="xsd:double"><xsl:value-of select="weight"/></xsl:attribute> 
                            </crm:P90F.has_value>
                            <!--  If there are two values for upper and lower range 
                            <ns2:PX_Max_Value name="xsd:double" />
                            <ns2:PX_Min_Value name="xsd:double" />
                            -->
                            <rdfs:label>Weight</rdfs:label>
                        </crm:E54.Dimension>
                    </crm:P43F.has_dimension>   
		</xsl:if>
		
		
		<crm:P104_is_subject_to >
				<crm:E30_Right>
					<crm:P3_has_note>Copyright the Portable Antiquities Scheme/British Museum</crm:P3_has_note>
				</crm:E30_Right>
		</crm:P104_is_subject_to>
                    
		
		
            
        <!--  End of CIDOC-CRM rdf -->     
      
        <!-- other refs -->
		
		<xsl:if test="otherRef != ''">
			<ns2:other_id>
				<xsl:value-of select="otherRef"/>
			</ns2:other_id>
		</xsl:if>
		<xsl:if test="treasureID != ''">
			<ns2:other_id>
				<xsl:value-of select="treasureID"/>
			</ns2:other_id>
		</xsl:if>
		<xsl:if test="otherRef != ''">	
			<ns2:reg_id>
				<xsl:value-of select="otherRef"/>
			</ns2:reg_id>
		</xsl:if>
		
		
		
		</crm:E22_Man-Made_Object>
		

		

		</foaf:primaryTopic>
	</foaf:Document> 
		<xsl:if test="objecttype = 'COIN'" >
			<nm:coin>
				
				<xsl:attribute name="rdf:about"><xsl:value-of select="$url"/><xsl:value-of select="id"/></xsl:attribute> 
				<dcterms:title><xsl:value-of select="old_findID" /></dcterms:title>
				<dcterms:identifier><xsl:value-of select="id" /></dcterms:identifier>

                                <xsl:if test="broadperiod = 'ROMAN'">
				<dcterms:partOf rdf:resource="http://nomisma.org/id/roman_numismatics"/>
                                </xsl:if>
				<nm:numismatic_term rdf:resource="http://nomisma.org/id/coin"/>
				
				<nm:collection>
                                    <xsl:attribute name="xsd:string">Portable Antiquities Scheme</xsl:attribute>
                                </nm:collection>
				
				<xsl:if test="die != ''" >
				<nm:axis rdf:datatype="xsd:integer"><xsl:value-of select="die" /></nm:axis>
				</xsl:if>
				<xsl:if test="diameter !=''">				
				<nm:diameter rdf:datatype="xsd:decimal"><xsl:value-of select="diameter"/></nm:diameter>
				</xsl:if>
				<xsl:if test="weight != ''">
				<nm:weight rdf:datatype="xsd:decimal"><xsl:value-of select="weight"/></nm:weight>
				</xsl:if>
				<xsl:if test="thickness != ''">
				<nm:thickness rdf:datatype="xsd:decimal"><xsl:value-of select="thickness"/></nm:thickness>
				</xsl:if>
				<xsl:if test="numdate1 != ''">
				<nm:start_date rdf:datatype="xsd:gYear"><xsl:value-of select="numdate1"/></nm:start_date>
				</xsl:if>
				<xsl:if test="numdate2 != ''">
				<nm:end_date rdf:datatype="xsd:gYear"><xsl:value-of select="numdate2"/></nm:end_date>
				</xsl:if>
				
				<nm:denomination>
					<rdf:Description>
						<xsl:attribute name="xsd:string"><xsl:value-of select="denomination"/></xsl:attribute>
						<owl:sameAs>http://dbpedia.org/page/<xsl:value-of select="denomination"/></owl:sameAs>				
					</rdf:Description>
				</nm:denomination>
			
				<nm:reverse>
					<nm:description>
						<xsl:attribute name="xsd:string">
							<xsl:value-of select="reverse_description"/>
						</xsl:attribute>
					</nm:description>
				</nm:reverse>
				
				<nm:reverse>
					<nm:legend>
						<xsl:attribute name="xsd:string">
							<xsl:value-of select="reverse_inscription"/>
						</xsl:attribute>
					</nm:legend>	
				</nm:reverse>
				
				<nm:obverse>
					<nm:description>
						<xsl:attribute name="xsd:string">
							<xsl:value-of select="obverse_description"/>
						</xsl:attribute>
					</nm:description>
				</nm:obverse>
				
				<nm:obverse>
					<nm:legend>
						<xsl:attribute name="xsd:string">
							<xsl:value-of select="obverse_inscription"/>
						</xsl:attribute>
					</nm:legend>	
				</nm:obverse>
				
				<nm:authority>
				<rdf:Description>
					<xsl:attribute name="rdf:about">http://finds.org.uk/database/terminology/rulers/ruler/id/<xsl:value-of select="ruler_id"/></xsl:attribute> 
						<rdfs:label rdf:datatype="xsd:string"><xsl:value-of select="ruler1"/></rdfs:label>
									<owl:sameAs >
										<xsl:attribute name="rdf:resource">http://dbpedia.org/page/<xsl:value-of select="rulerDbpedia"/></xsl:attribute>
									</owl:sameAs>
									<owl:sameAs >
										<xsl:attribute name="rdf:resource">http://viaf.org/viaf/<xsl:value-of select="viaf"/></xsl:attribute>
									</owl:sameAs>
									<owl:sameAs>
										<xsl:attribute name="rdf:resource">http://nomisma.org/id/<xsl:value-of select="nomismaRulerID"/></xsl:attribute>
									</owl:sameAs>
					</rdf:Description>
				</nm:authority>
				
				<xsl:choose>
					<xsl:when test="knownas = ''" >
						<nm:findspot>
						      <rdf:Description>
						      	<gn:parentCountry rdf:resource="http://www.geonames.org/2635167"/>
						        <geo:lat><xsl:value-of select="fourFigureLat"/></geo:lat>
                                                        <geo:long><xsl:value-of select="fourFigureLon"/></geo:long>
								<owl:sameAs>
				        			<xsl:attribute name="rdf:resource">http://where.yahooapis.com/v1/place/<xsl:value-of select="woeid"/></xsl:attribute>
				       			</owl:sameAs>
						      </rdf:Description>
						</nm:findspot>
					</xsl:when>
					<xsl:otherwise>
						<nm:findspot>
						      <rdf:Description>
						      	<gn:parentCountry rdf:resource="http://www.geonames.org/2635167"/>
						      </rdf:Description>
						</nm:findspot>
					</xsl:otherwise>
				</xsl:choose>
				
					
				
				<xsl:if test="mint_name != ''" >
					<nm:mint>
					<rdf:Description>
					<xsl:attribute name="rdf:about">http://finds.org.uk/database/terminology/mints/mint/id/<xsl:value-of select="mint_id"/></xsl:attribute>
						<rdfs:label rdf:datatype="xsd:string"><xsl:value-of select="mint_name"/></rdfs:label> 
						<owl:sameAs>
							<xsl:attribute name="rdf:resource">http://nomisma.org/id/<xsl:value-of select="nomismaMintID"/></xsl:attribute>
						</owl:sameAs>
						<owl:sameAs>
							<xsl:attribute name="rdf:resource">http://where.yahooapis.com/v1/place/<xsl:value-of select="mintWoeid"/></xsl:attribute>
						</owl:sameAs>
						<owl:sameAs>
							<xsl:attribute name="rdf:resource">http://geonames.org/<xsl:value-of select="mintGeonamesID"/></xsl:attribute>
						</owl:sameAs>
						<owl:sameAs >
							<xsl:attribute name="rdf:resource">http://pleiades.stoa.org/places/<xsl:value-of select="pleiadesID"/>#this</xsl:attribute>
						</owl:sameAs>
					</rdf:Description>
					</nm:mint>
				</xsl:if>	
				
				<xsl:if test="moneyer != ''">	
				<nm:moneyer>
					<rdf:Description>
					<xsl:attribute name="rdf:about">http://finds.org.uk/database/terminology/moneyers/moneyer/id/<xsl:value-of select="mint_id"/></xsl:attribute>
					<rdfs:label rdf:datatype="xsd:string"><xsl:value-of select="moneyer"/></rdfs:label>
						<owl:sameAs >
							<xsl:attribute name="rdf:resource">http://dbpedia.org/page/<xsl:value-of select="rulerDbpedia"/></xsl:attribute>
						</owl:sameAs>
					</rdf:Description>
				</nm:moneyer>
				</xsl:if>
				
				<xsl:if test="reeceID != ''">			
				<nm:reecePeriod>
					<rdf:Description>
					<xsl:attribute name="rdf:about">http://finds.org.uk/romancoins/reeceperiods/period/id/<xsl:value-of select="reeceID"/></xsl:attribute>
					<rdfs:label rdf:datatype="xsd:string"><xsl:value-of select="period_name" /> - <xsl:value-of select="date_range" /></rdfs:label>
					<owl:sameAs>
                                            <xsl:attribute name="rdf:resource">http://nomisma.org/id/reeceperiod<xsl:value-of select="reeceID"/></xsl:attribute>
					</owl:sameAs>
                                        <rdfs:comment>Note this identifier does not yet exist!</rdfs:comment>
					</rdf:Description>
				</nm:reecePeriod>
				</xsl:if>
				
				<xsl:if test="reverseType != ''">	
				<nm:reverseType>
					<rdf:Description>
					<xsl:attribute name="rdf:about">http://finds.org.uk/romancoins/reversetypes/type/id/<xsl:value-of select="reverseType"/></xsl:attribute>
                                            <rdfs:label rdf:datatype="xsd:string"><xsl:value-of select="reverseType" /></rdfs:label>
					</rdf:Description>
				</nm:reverseType>
				</xsl:if>					
			</nm:coin>
			
		</xsl:if>
	</xsl:template>

</xsl:stylesheet>